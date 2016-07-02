--
-- PostgreSQL database dump
--

-- Dumped from database version 9.4.7
-- Dumped by pg_dump version 9.5.0

-- Started on 2016-06-30 00:27:48

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 197 (class 3079 OID 11893)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2200 (class 0 OID 0)
-- Dependencies: 197
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- TOC entry 619 (class 1247 OID 65719)
-- Name: e_ternary_position; Type: TYPE; Schema: public; Owner: homestead
--

CREATE TYPE e_ternary_position AS ENUM (
    'L',
    'M',
    'R'
);


ALTER TYPE e_ternary_position OWNER TO homestead;

--
-- TOC entry 613 (class 1247 OID 65777)
-- Name: t_matrix_node_simple; Type: TYPE; Schema: public; Owner: homestead
--

CREATE TYPE t_matrix_node_simple AS (
	user_id integer,
	level_abs integer,
	parent_id integer,
	root_path_array integer[],
	children_positions integer[],
	has_free_place boolean,
	is_leaf boolean,
	free_places boolean[]
);


ALTER TYPE t_matrix_node_simple OWNER TO homestead;

--
-- TOC entry 616 (class 1247 OID 65780)
-- Name: t_matrix_position; Type: TYPE; Schema: public; Owner: homestead
--

CREATE TYPE t_matrix_position AS (
	user_id integer,
	parent_level integer,
	positions_array e_ternary_position[],
	is_leaf boolean,
	child_level integer
);


ALTER TYPE t_matrix_position OWNER TO homestead;

--
-- TOC entry 216 (class 1255 OID 65799)
-- Name: ternary_add_to_parent(integer, integer); Type: FUNCTION; Schema: public; Owner: homestead
--

CREATE FUNCTION ternary_add_to_parent(parentid integer, newchildid integer) RETURNS integer
    LANGUAGE plpgsql
    AS $$
DECLARE
  --is_parent_exists  BOOL :=FALSE;
  is_free_place     BOOL :=FALSE;

  node                 t_matrix_node_simple;
  next_node       RECORD;
  /*  a (user_id INTEGER, user_id INTEGER, level_abs INTEGER, parent_id INTEGER,
    root_path_array INTEGER [], children_positions INTEGER [], has_free_place BOOL,
    is_leaf BOOL, free_places BOOLEAN []);*/
  existing_children INTEGER ARRAY;
  children_count    INTEGER;
  filled_pos_arr    CHARACTER [];
  --numtimes          INTEGER :=3;
  new_node_id       INTEGER;
  new_position      E_TERNARY_POSITION;
  --new_depth         INTEGER;
  --old_pos           CHARACTER;
  no_places         BOOLEAN :=TRUE;
  new_node tree_ternary%ROWTYPE;
  next_level INTEGER :=1;
  free_level_min INTEGER:=1;
  free_level_max INTEGER:=1;
BEGIN
  -- Simple add to parent
--    Uses ternary_load_matrix_position(integer)
--
--        Check if parent exists
--        False
--           Return -1 , should be exception ?
--        True
--         Checks if is_leaf=true
--            True - add to first position from [L,M,R] values
--          Not leaf
--            Intersect [L,M,R] with
  SELECT * INTO node   FROM ternary_load_matrix_node_simple(parentId);

  IF (NOT FOUND)
  THEN
    RAISE EXCEPTION 'Parent ID % not found', parentId;
    RETURN -1;
  END IF;

  RAISE NOTICE 'Parent found: %, level: %, has free place: %, leaf: %',
      FOUND, node.level_abs, node.has_free_place, node.is_leaf;

  IF EXISTS(SELECT 1 FROM tree_ternary WHERE user_id = newChildId) THEN
      RAISE EXCEPTION 'Child ID: % exists!', newChildId;
  END IF;

  IF NOT node.has_free_place THEN
        -- DEEP SHIT GOES HERER

        RAISE WARNING 'ParentID: %, level: % has no free place', parentId,node.level_abs;

     -- FIND MIN AND MAX LEVEL with free items
         SELECT DISTINCT min(t1.level_abs) OVER(PARTITION BY 0), max(t1.level_abs) OVER (PARTITION BY 0)
         INTO free_level_min,free_level_max FROM tree_ternary t1 WHERE (ARRAY[l,m,r] @> ARRAY[user_id]);

        RAISE NOTICE 'Free level min: %, max: %',free_level_min, free_level_max;

        if (free_level_min>node.level_abs) THEN
          node.level_abs:=free_level_min;
        END IF;

        -- find on this level, and only after go to next level
        select user_id,parent_id, level_abs, position_on_level,is_leaf, free_places, root_path_array    --row_number() OVER (PARTITION BY 0)
        FROM ternary_get_nodes_with_places_by_level(node.level_abs) INTO next_node
        ORDER BY created_at, position_on_level; -- create_at timestamp, position_on_level kinda DB timestamp, can rely on them

        next_level:=node.level_abs;



        IF NOT FOUND THEN
            next_level:=node.level_abs+1;
            RAISE WARNING 'We should go to from current level: % is full, next level: %', node.level_abs, next_level;
        END IF;

        select user_id,parent_id, level_abs, position_on_level,is_leaf, free_places, root_path_array
        FROM ternary_get_nodes_with_places_by_level(next_level) INTO next_node
        ORDER BY created_at, position_on_level;

        IF NOT FOUND THEN
            RAISE EXCEPTION 'We have nothing on level: % , to add node: %, which parent_is %', next_level,newChildId,parentId;

              -- WE JUST NEED TO FIND FIRST EMPTY LEVEL
            RETURN -1;

        END IF;


        RAISE WARNING 'Found for another parent id: %, level: % seems has places %', next_node.user_id,next_level, next_node.free_places;


        -- assign next_node to node
        node.free_places := next_node.free_places;
        node.parent_id   := next_node.user_id;
        node.root_path_array   := next_node.root_path_array;
        node.level_abs := next_node.level_abs;
        parentId:=next_node.user_id;
        --node.level_abs := next_node.free_places;

  END IF;


  RAISE NOTICE 'Parentid now is: %',parentId;

  SELECT t_position INTO new_position
    FROM (SELECT
            unnest(ARRAY ['L', 'M', 'R']) AS t_position,
            unnest(node.free_places)  AS is_free) AS t
    WHERE t.is_free = TRUE LIMIT 1;

  RAISE NOTICE 'Free position found: %, new position: %', FOUND, new_position;

  -- one more check
  SELECT new_position = ANY(ARRAY ['L', 'M', 'R']::e_ternary_position[]) INTO is_free_place;
    RAISE NOTICE 'Free position check correct: %',is_free_place;


  new_node.parent_id:= parentId;
  new_node.user_id:= newChildId;
  new_node.t_position:= new_position;
  new_node.level_abs:= node.level_abs;



  IF is_free_place THEN
    SELECT * FROM ternary_insert_new_node(node, new_node) INTO new_node_id;
    RETURN new_node_id;

  END IF;

  RETURN -1;
END;

$$;


ALTER FUNCTION public.ternary_add_to_parent(parentid integer, newchildid integer) OWNER TO homestead;

--
-- TOC entry 212 (class 1255 OID 65781)
-- Name: ternary_get_ancestors(integer); Type: FUNCTION; Schema: public; Owner: homestead
--

CREATE FUNCTION ternary_get_ancestors(nodeid integer) RETURNS TABLE(id integer)
    LANGUAGE plpgsql
    AS $$
  DECLARE
  var_temp  BOOL :=FALSE;
BEGIN
  RETURN QUERY

  SELECT * FROM  (SELECT regexp_split_to_table(tt.path_to_root,',')::INTEGER as id
               FROM tree_ternary tt WHERE user_id = nodeId) as all_ancestors

    WHERE all_ancestors.id <> nodeId;

END

$$;


ALTER FUNCTION public.ternary_get_ancestors(nodeid integer) OWNER TO homestead;

--
-- TOC entry 211 (class 1255 OID 65782)
-- Name: ternary_get_ancestors_array(integer); Type: FUNCTION; Schema: public; Owner: homestead
--

CREATE FUNCTION ternary_get_ancestors_array(nodeid integer) RETURNS integer[]
    LANGUAGE plpgsql
    AS $$
  DECLARE
  var_temp  BOOL :=FALSE;
BEGIN
  --SELECT unnest(t) as id FROM ternary_get_ancestors_array(7) as t

    RETURN array_remove(string_to_array(tt.path_to_root,',')::INTEGER[],nodeId)
  FROM tree_ternary tt WHERE user_id = nodeId;


END

$$;


ALTER FUNCTION public.ternary_get_ancestors_array(nodeid integer) OWNER TO homestead;

--
-- TOC entry 214 (class 1255 OID 65790)
-- Name: ternary_get_nodes_with_places_by_level(integer); Type: FUNCTION; Schema: public; Owner: homestead
--

CREATE FUNCTION ternary_get_nodes_with_places_by_level(absolutelevel integer) RETURNS TABLE(user_id integer, parent_id integer, created_at timestamp without time zone, level_abs integer, position_on_level integer, root_path_array integer[], children_positions integer[], is_leaf boolean, free_places boolean[])
    LANGUAGE sql
    AS $$

  SELECT t1.user_id                                      ,
    t1.parent_id,
    t1.created_at,
    t1.level_abs,
    t1.position_on_level,
    string_to_array(t1.path_to_root, ',')::INTEGER[]            AS root_path_array,
    ARRAY [t1.l, t1.m, t1.r]                         AS children_positions,
    ARRAY [t1.l, t1.m, t1.r] = ARRAY [user_id, user_id, user_id] AS is_leaf,
    ARRAY [t1.l = user_id, t1.m = user_id, t1.r = user_id]       AS free_places
  FROM tree_ternary t1 WHERE level_abs=absoluteLevel AND (ARRAY[l,m,r] @> ARRAY[user_id]);
$$;


ALTER FUNCTION public.ternary_get_nodes_with_places_by_level(absolutelevel integer) OWNER TO homestead;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 194 (class 1259 OID 65754)
-- Name: tree_ternary; Type: TABLE; Schema: public; Owner: homestead
--

CREATE TABLE tree_ternary (
    user_id integer NOT NULL,
    parent_id integer NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    t_position e_ternary_position NOT NULL,
    level_abs integer,
    l integer NOT NULL,
    m integer NOT NULL,
    r integer NOT NULL,
    position_on_level integer,
    path_to_root character varying DEFAULT '1'::character varying NOT NULL,
    CONSTRAINT ternary_max_on_level CHECK (((((3)::double precision ^ (level_abs)::double precision) - (position_on_level)::double precision) >= (0)::double precision))
);


ALTER TABLE tree_ternary OWNER TO homestead;

--
-- TOC entry 2201 (class 0 OID 0)
-- Dependencies: 194
-- Name: COLUMN tree_ternary.t_position; Type: COMMENT; Schema: public; Owner: homestead
--

COMMENT ON COLUMN tree_ternary.t_position IS 'Position in ternary TREE';


--
-- TOC entry 2202 (class 0 OID 0)
-- Dependencies: 194
-- Name: COLUMN tree_ternary.level_abs; Type: COMMENT; Schema: public; Owner: homestead
--

COMMENT ON COLUMN tree_ternary.level_abs IS 'Absolute depth level from root';


--
-- TOC entry 2203 (class 0 OID 0)
-- Dependencies: 194
-- Name: COLUMN tree_ternary.l; Type: COMMENT; Schema: public; Owner: homestead
--

COMMENT ON COLUMN tree_ternary.l IS 'Children position in L, default = user_id';


--
-- TOC entry 2204 (class 0 OID 0)
-- Dependencies: 194
-- Name: COLUMN tree_ternary.m; Type: COMMENT; Schema: public; Owner: homestead
--

COMMENT ON COLUMN tree_ternary.m IS 'Children position in M, default = user_id';


--
-- TOC entry 2205 (class 0 OID 0)
-- Dependencies: 194
-- Name: COLUMN tree_ternary.r; Type: COMMENT; Schema: public; Owner: homestead
--

COMMENT ON COLUMN tree_ternary.r IS 'Children position in R, default = user_id';


--
-- TOC entry 2206 (class 0 OID 0)
-- Dependencies: 194
-- Name: COLUMN tree_ternary.position_on_level; Type: COMMENT; Schema: public; Owner: homestead
--

COMMENT ON COLUMN tree_ternary.position_on_level IS 'Position on level, we can count it, then decide if level is full or not';


--
-- TOC entry 2207 (class 0 OID 0)
-- Dependencies: 194
-- Name: COLUMN tree_ternary.path_to_root; Type: COMMENT; Schema: public; Owner: homestead
--

COMMENT ON COLUMN tree_ternary.path_to_root IS 'Path to root (materialized path)';


--
-- TOC entry 215 (class 1255 OID 65794)
-- Name: ternary_insert_new_node(t_matrix_node_simple, tree_ternary); Type: FUNCTION; Schema: public; Owner: homestead
--

CREATE FUNCTION ternary_insert_new_node(pnode t_matrix_node_simple, nnode tree_ternary) RETURNS integer
    LANGUAGE plpgsql
    AS $_$
DECLARE
  new_node          tree_ternary%ROWTYPE;
  existing_children INTEGER ARRAY;
  new_position      E_TERNARY_POSITION;
  new_node_id       tree_ternary.user_id%TYPE;
  added_node_id     tree_ternary.user_id%TYPE;
  parent_node_id    INTEGER;
  update_stmt TEXT;
BEGIN

  new_node_id:=nNode.user_id;
  parent_node_id:=nNode.parent_id;
  RAISE NOTICE 'Insert newNode: % ,position:% , level: %,as child to parentNode %',
  new_node_id, nNode.t_position::CHARACTER, nNode.level_abs, parent_node_id;

  nNode.path_to_root:=array_to_string(array_prepend(new_node_id, pNode.root_path_array), ',');
  nNode.level_abs = pNode.level_abs + 1;
  nNode.l = new_node_id;
  nNode.m = new_node_id;
  nNode.r = new_node_id;

  RAISE NOTICE 'Set newNode path_to_root % , level_abs:% , l,m,r - to default %',
  nNode.path_to_root, nNode.level_abs, new_node_id;

  --places on levels query
  /*  SELECT level_abs,max(position_on_level) as last_on_level, pow(3,level_abs) as level_limit, pow(3,level_abs) - max(position_on_level) as places_on_level
      FROM tree_ternary
    GROUP BY level_abs ORDER BY level_abs; */

  IF EXISTS (SELECT 1 FROM tree_ternary WHERE level_abs = nNode.level_abs GROUP BY level_abs) THEN
      WITH pos_on_level AS (
          SELECT
            max(position_on_level)                     AS last_on_level,
            pow(3, level_abs)                          AS level_limit,
            pow(3, level_abs) - max(position_on_level) AS places_on_level
          FROM tree_ternary
          WHERE level_abs = nNode.level_abs
          GROUP BY level_abs
      )
      SELECT CASE
             WHEN last_on_level ISNULL THEN 1
             ELSE last_on_level+1 END
      FROM pos_on_level
      INTO nNode.position_on_level;
  ELSE
    nNode.position_on_level:=1;
  END IF;

  RAISE NOTICE 'Set newNode.position_on_level %', nNode.position_on_level;


  WITH inserted AS (
    INSERT INTO tree_ternary (parent_id, user_id, t_position, level_abs, l, m, r, position_on_level, path_to_root)
    --INSERT INTO tree_ternary
    VALUES (nNode.parent_id, nNode.user_id,nNode.t_position,nNode.level_abs,nNode.l,
            nNode.m,nNode.r,nNode.position_on_level,nNode.path_to_root)
    --SELECT (nNode).*
    RETURNING *
  )

  SELECT inserted.user_id
  FROM inserted
  INTO added_node_id;


    SELECT format('UPDATE tree_ternary SET %I = $1 WHERE user_id=$2',t1.t_pos) INTO update_stmt FROM
    (SELECT
       unnest(ARRAY['l','m','r']) as t_pos,
       unnest(ARRAY[l,m,r]) AS t_cid
     FROM tree_ternary t
 WHERE t.user_id = parent_node_id ) as t1 WHERE t_cid=parent_node_id LIMIT 1;

   RAISE WARNING 'Update stmt UPDATE tree_ternary SET % = % WHERE user_id=%','LETTER',new_node_id,parent_node_id;

  EXECUTE update_stmt USING new_node_id,parent_node_id;

  RAISE WARNING 'If row updated %',FOUND;



  RETURN added_node_id;


END;

$_$;


ALTER FUNCTION public.ternary_insert_new_node(pnode t_matrix_node_simple, nnode tree_ternary) OWNER TO homestead;

--
-- TOC entry 213 (class 1255 OID 65785)
-- Name: ternary_load_matrix_node_simple(integer); Type: FUNCTION; Schema: public; Owner: homestead
--

CREATE FUNCTION ternary_load_matrix_node_simple(pid integer) RETURNS SETOF t_matrix_node_simple
    LANGUAGE plpgsql
    AS $$
DECLARE

  r T_MATRIX_NODE_SIMPLE;
BEGIN

  RETURN QUERY SELECT
    t1.user_id                                      AS user_id,
    t1.level_abs,
    t1.parent_id,
    string_to_array(t1.path_to_root, ',')::INTEGER[]            AS root_path_array,
    ARRAY [t1.l, t1.m, t1.r]                         AS children_positions,
    ARRAY [t1.l, t1.m, t1.r] @> ARRAY [pid]          AS has_free_place,
    ARRAY [t1.l, t1.m, t1.r] = ARRAY [pid, pid, pid] AS is_leaf,
    ARRAY [t1.l = pid, t1.m = pid, t1.r = pid]       AS free_places
  --INTO r
  FROM tree_ternary t1
  WHERE t1.user_id = pid;
  --RETURN r;

END;
$$;


ALTER FUNCTION public.ternary_load_matrix_node_simple(pid integer) OWNER TO homestead;

--
-- TOC entry 210 (class 1255 OID 65568)
-- Name: ternary_trigger_handler_add_matrix_relation(); Type: FUNCTION; Schema: public; Owner: homestead
--

CREATE FUNCTION ternary_trigger_handler_add_matrix_relation() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
  IF TG_OP = 'INSERT' THEN
    RAISE WARNING 'We have inserted the linear user %',NEW.user_id;
  ELSEIF TG_OP = 'UPDATE' THEN
    --CHECK THAT user_id has no parent id, and depth = 0 and left-rgt = 1
    --and parent_id supplied
    -- after call tt_add_to_matrix(user_id, parent_id)
    RAISE WARNING 'We have updated the linear user %',NEW.user_id;
  END IF;

  RETURN new;
END ;
$$;


ALTER FUNCTION public.ternary_trigger_handler_add_matrix_relation() OWNER TO homestead;

--
-- TOC entry 180 (class 1259 OID 50163)
-- Name: cache; Type: TABLE; Schema: public; Owner: homestead
--

CREATE TABLE cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE cache OWNER TO homestead;

--
-- TOC entry 174 (class 1259 OID 16388)
-- Name: migrations; Type: TABLE; Schema: public; Owner: homestead
--

CREATE TABLE migrations (
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE migrations OWNER TO homestead;

--
-- TOC entry 182 (class 1259 OID 50173)
-- Name: packages; Type: TABLE; Schema: public; Owner: homestead
--

CREATE TABLE packages (
    id integer NOT NULL,
    name character varying(100) NOT NULL,
    start_balance integer NOT NULL,
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE packages OWNER TO homestead;

--
-- TOC entry 181 (class 1259 OID 50171)
-- Name: packages_id_seq; Type: SEQUENCE; Schema: public; Owner: homestead
--

CREATE SEQUENCE packages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE packages_id_seq OWNER TO homestead;

--
-- TOC entry 2208 (class 0 OID 0)
-- Dependencies: 181
-- Name: packages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: homestead
--

ALTER SEQUENCE packages_id_seq OWNED BY packages.id;


--
-- TOC entry 177 (class 1259 OID 50146)
-- Name: password_resets; Type: TABLE; Schema: public; Owner: homestead
--

CREATE TABLE password_resets (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE password_resets OWNER TO homestead;

--
-- TOC entry 185 (class 1259 OID 50194)
-- Name: profiles; Type: TABLE; Schema: public; Owner: homestead
--

CREATE TABLE profiles (
    id integer NOT NULL,
    user_id integer NOT NULL,
    package_id integer NOT NULL,
    country_code character varying(4),
    city_id character varying(255),
    additional json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE profiles OWNER TO homestead;

--
-- TOC entry 184 (class 1259 OID 50192)
-- Name: profiles_id_seq; Type: SEQUENCE; Schema: public; Owner: homestead
--

CREATE SEQUENCE profiles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE profiles_id_seq OWNER TO homestead;

--
-- TOC entry 2209 (class 0 OID 0)
-- Dependencies: 184
-- Name: profiles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: homestead
--

ALTER SEQUENCE profiles_id_seq OWNED BY profiles.id;


--
-- TOC entry 183 (class 1259 OID 50184)
-- Name: sessions; Type: TABLE; Schema: public; Owner: homestead
--

CREATE TABLE sessions (
    id character varying(255) NOT NULL,
    user_id integer,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE sessions OWNER TO homestead;

--
-- TOC entry 193 (class 1259 OID 50263)
-- Name: support_tickets; Type: TABLE; Schema: public; Owner: homestead
--

CREATE TABLE support_tickets (
    id integer NOT NULL,
    user_id integer NOT NULL,
    status integer NOT NULL,
    operator_id integer,
    subject character varying(255) NOT NULL,
    message text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE support_tickets OWNER TO homestead;

--
-- TOC entry 192 (class 1259 OID 50261)
-- Name: support_tickets_id_seq; Type: SEQUENCE; Schema: public; Owner: homestead
--

CREATE SEQUENCE support_tickets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE support_tickets_id_seq OWNER TO homestead;

--
-- TOC entry 2210 (class 0 OID 0)
-- Dependencies: 192
-- Name: support_tickets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: homestead
--

ALTER SEQUENCE support_tickets_id_seq OWNED BY support_tickets.id;


--
-- TOC entry 191 (class 1259 OID 50245)
-- Name: tree_binary; Type: TABLE; Schema: public; Owner: homestead
--

CREATE TABLE tree_binary (
    id integer NOT NULL,
    parent_id integer,
    child_id integer NOT NULL,
    l_child_id integer,
    r_child_id integer,
    refer_id integer,
    depth integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE tree_binary OWNER TO homestead;

--
-- TOC entry 190 (class 1259 OID 50243)
-- Name: tree_binary_id_seq; Type: SEQUENCE; Schema: public; Owner: homestead
--

CREATE SEQUENCE tree_binary_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tree_binary_id_seq OWNER TO homestead;

--
-- TOC entry 2211 (class 0 OID 0)
-- Dependencies: 190
-- Name: tree_binary_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: homestead
--

ALTER SEQUENCE tree_binary_id_seq OWNED BY tree_binary.id;


--
-- TOC entry 189 (class 1259 OID 50222)
-- Name: tree_linear; Type: TABLE; Schema: public; Owner: homestead
--

CREATE TABLE tree_linear (
    id integer NOT NULL,
    user_id integer NOT NULL,
    parent_id integer,
    lft integer,
    rgt integer,
    depth integer,
    info json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE tree_linear OWNER TO homestead;

--
-- TOC entry 188 (class 1259 OID 50220)
-- Name: tree_linear_id_seq; Type: SEQUENCE; Schema: public; Owner: homestead
--

CREATE SEQUENCE tree_linear_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tree_linear_id_seq OWNER TO homestead;

--
-- TOC entry 2212 (class 0 OID 0)
-- Dependencies: 188
-- Name: tree_linear_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: homestead
--

ALTER SEQUENCE tree_linear_id_seq OWNED BY tree_linear.id;


--
-- TOC entry 176 (class 1259 OID 50135)
-- Name: users; Type: TABLE; Schema: public; Owner: homestead
--

CREATE TABLE users (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE users OWNER TO homestead;

--
-- TOC entry 175 (class 1259 OID 50133)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: homestead
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE users_id_seq OWNER TO homestead;

--
-- TOC entry 2213 (class 0 OID 0)
-- Dependencies: 175
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: homestead
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- TOC entry 187 (class 1259 OID 50206)
-- Name: users_transactions; Type: TABLE; Schema: public; Owner: homestead
--

CREATE TABLE users_transactions (
    id integer NOT NULL,
    user_id integer NOT NULL,
    amount integer NOT NULL,
    type integer NOT NULL,
    status integer NOT NULL,
    sender_id integer NOT NULL,
    reciever_id integer NOT NULL,
    debit_flag boolean NOT NULL,
    credit_flag boolean NOT NULL,
    comment character varying(255),
    hash character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE users_transactions OWNER TO homestead;

--
-- TOC entry 186 (class 1259 OID 50204)
-- Name: users_transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: homestead
--

CREATE SEQUENCE users_transactions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE users_transactions_id_seq OWNER TO homestead;

--
-- TOC entry 2214 (class 0 OID 0)
-- Dependencies: 186
-- Name: users_transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: homestead
--

ALTER SEQUENCE users_transactions_id_seq OWNED BY users_transactions.id;


--
-- TOC entry 179 (class 1259 OID 50156)
-- Name: users_wallets; Type: TABLE; Schema: public; Owner: homestead
--

CREATE TABLE users_wallets (
    id integer NOT NULL,
    user_id integer NOT NULL,
    type smallint NOT NULL,
    comment character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE users_wallets OWNER TO homestead;

--
-- TOC entry 178 (class 1259 OID 50154)
-- Name: users_wallets_id_seq; Type: SEQUENCE; Schema: public; Owner: homestead
--

CREATE SEQUENCE users_wallets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE users_wallets_id_seq OWNER TO homestead;

--
-- TOC entry 2215 (class 0 OID 0)
-- Dependencies: 178
-- Name: users_wallets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: homestead
--

ALTER SEQUENCE users_wallets_id_seq OWNED BY users_wallets.id;


--
-- TOC entry 2011 (class 2604 OID 50176)
-- Name: id; Type: DEFAULT; Schema: public; Owner: homestead
--

ALTER TABLE ONLY packages ALTER COLUMN id SET DEFAULT nextval('packages_id_seq'::regclass);


--
-- TOC entry 2012 (class 2604 OID 50197)
-- Name: id; Type: DEFAULT; Schema: public; Owner: homestead
--

ALTER TABLE ONLY profiles ALTER COLUMN id SET DEFAULT nextval('profiles_id_seq'::regclass);


--
-- TOC entry 2016 (class 2604 OID 50266)
-- Name: id; Type: DEFAULT; Schema: public; Owner: homestead
--

ALTER TABLE ONLY support_tickets ALTER COLUMN id SET DEFAULT nextval('support_tickets_id_seq'::regclass);


--
-- TOC entry 2015 (class 2604 OID 50248)
-- Name: id; Type: DEFAULT; Schema: public; Owner: homestead
--

ALTER TABLE ONLY tree_binary ALTER COLUMN id SET DEFAULT nextval('tree_binary_id_seq'::regclass);


--
-- TOC entry 2014 (class 2604 OID 50225)
-- Name: id; Type: DEFAULT; Schema: public; Owner: homestead
--

ALTER TABLE ONLY tree_linear ALTER COLUMN id SET DEFAULT nextval('tree_linear_id_seq'::regclass);


--
-- TOC entry 2009 (class 2604 OID 50138)
-- Name: id; Type: DEFAULT; Schema: public; Owner: homestead
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- TOC entry 2013 (class 2604 OID 50209)
-- Name: id; Type: DEFAULT; Schema: public; Owner: homestead
--

ALTER TABLE ONLY users_transactions ALTER COLUMN id SET DEFAULT nextval('users_transactions_id_seq'::regclass);


--
-- TOC entry 2010 (class 2604 OID 50159)
-- Name: id; Type: DEFAULT; Schema: public; Owner: homestead
--

ALTER TABLE ONLY users_wallets ALTER COLUMN id SET DEFAULT nextval('users_wallets_id_seq'::regclass);


--
-- TOC entry 2178 (class 0 OID 50163)
-- Dependencies: 180
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: homestead
--

COPY cache (key, value, expiration) FROM stdin;
\.


--
-- TOC entry 2172 (class 0 OID 16388)
-- Dependencies: 174
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: homestead
--

COPY migrations (migration, batch) FROM stdin;
2014_10_12_000000_create_users_table	1
2014_10_12_100000_create_password_resets_table	1
2016_06_01_103027_create_users_wallets_table	1
2016_06_01_105546_create_cache_table	1
2016_06_01_155305_create_packages_table	1
2016_06_01_195846_create_sessions_table	1
2016_06_01_202654_create_profiles_table	1
2016_06_01_224213_create_users_transactions_table	1
2016_06_03_233348_create_tree_linear_table	1
2016_06_15_022318_create_tree_binary_table	1
2016_06_18_005400_create_support_tickets_table	1
\.


--
-- TOC entry 2180 (class 0 OID 50173)
-- Dependencies: 182
-- Data for Name: packages; Type: TABLE DATA; Schema: public; Owner: homestead
--

COPY packages (id, name, start_balance, description, created_at, updated_at) FROM stdin;
1	Dr. Brock Howell I	393	Rerum eos officia sit sint est.	2016-06-22 02:41:44	2016-06-22 02:41:44
2	Evalyn Herman	541	Aspernatur labore sit voluptate.	2016-06-22 02:41:44	2016-06-22 02:41:44
3	Olen Buckridge	537	Alias doloremque molestias enim distinctio dolorum voluptas et.	2016-06-22 02:41:44	2016-06-22 02:41:44
4	Jeremie Hickle	498	Omnis et maxime sint ut sit.	2016-06-22 02:41:44	2016-06-22 02:41:44
5	Cristopher Kemmer	572	Dolores totam explicabo voluptatibus corrupti atque aut molestiae.	2016-06-22 02:41:44	2016-06-22 02:41:44
\.


--
-- TOC entry 2216 (class 0 OID 0)
-- Dependencies: 181
-- Name: packages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: homestead
--

SELECT pg_catalog.setval('packages_id_seq', 5, true);


--
-- TOC entry 2175 (class 0 OID 50146)
-- Dependencies: 177
-- Data for Name: password_resets; Type: TABLE DATA; Schema: public; Owner: homestead
--

COPY password_resets (email, token, created_at) FROM stdin;
\.


--
-- TOC entry 2183 (class 0 OID 50194)
-- Dependencies: 185
-- Data for Name: profiles; Type: TABLE DATA; Schema: public; Owner: homestead
--

COPY profiles (id, user_id, package_id, country_code, city_id, additional, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 2217 (class 0 OID 0)
-- Dependencies: 184
-- Name: profiles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: homestead
--

SELECT pg_catalog.setval('profiles_id_seq', 1, false);


--
-- TOC entry 2181 (class 0 OID 50184)
-- Dependencies: 183
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: homestead
--

COPY sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
2453986c259817fb68654eb00d0de816e07d201f	1	192.168.10.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36	YTo2OntzOjY6Il90b2tlbiI7czo0MDoiY1NJdENKZ2pFY2hQS0hUMGNJZGVDZnd5SU1IRW9FVGRNUHVTMUtMbyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9idGNjLXYvcGFydG5lciI7fXM6NToiZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjIyOiJQSFBERUJVR0JBUl9TVEFDS19EQVRBIjthOjA6e31zOjk6Il9zZjJfbWV0YSI7YTozOntzOjE6InUiO2k6MTQ2NjY4MTM1MTtzOjE6ImMiO2k6MTQ2NjY4MTMxNDtzOjE6ImwiO3M6MToiMCI7fX0=	1466681352
fe17a6a4b2552490378d1db6095f8af6046a9633	1	192.168.10.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36	YTo2OntzOjY6Il90b2tlbiI7czo0MDoiOWVVNGQzQjFvU1gzeFBUWkhBc1pVM0hiMG56a044MUpROTBkMHZaTyI7czo1OiJmbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyODoiaHR0cDovL2J0Y2Mtdi9wYXJ0bmVyL2NyZWF0ZSI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoyMjoiUEhQREVCVUdCQVJfU1RBQ0tfREFUQSI7YTowOnt9czo5OiJfc2YyX21ldGEiO2E6Mzp7czoxOiJ1IjtpOjE0NjY1NjQzNTQ7czoxOiJjIjtpOjE0NjY1NjM1ODg7czoxOiJsIjtzOjE6IjAiO319	1466564354
\.


--
-- TOC entry 2191 (class 0 OID 50263)
-- Dependencies: 193
-- Data for Name: support_tickets; Type: TABLE DATA; Schema: public; Owner: homestead
--

COPY support_tickets (id, user_id, status, operator_id, subject, message, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 2218 (class 0 OID 0)
-- Dependencies: 192
-- Name: support_tickets_id_seq; Type: SEQUENCE SET; Schema: public; Owner: homestead
--

SELECT pg_catalog.setval('support_tickets_id_seq', 1, false);


--
-- TOC entry 2189 (class 0 OID 50245)
-- Dependencies: 191
-- Data for Name: tree_binary; Type: TABLE DATA; Schema: public; Owner: homestead
--

COPY tree_binary (id, parent_id, child_id, l_child_id, r_child_id, refer_id, depth, created_at, updated_at) FROM stdin;
1	0	1	\N	\N	\N	0	\N	\N
2	1	2	\N	\N	\N	1	\N	\N
3	1	3	\N	\N	\N	1	\N	\N
4	2	4	\N	\N	\N	2	\N	\N
5	2	5	\N	\N	\N	2	\N	\N
6	3	6	\N	\N	\N	2	\N	\N
7	4	7	\N	\N	\N	3	\N	\N
8	5	8	\N	\N	\N	3	\N	\N
9	5	9	\N	\N	\N	3	\N	\N
10	3	10	\N	\N	\N	2	\N	\N
\.


--
-- TOC entry 2219 (class 0 OID 0)
-- Dependencies: 190
-- Name: tree_binary_id_seq; Type: SEQUENCE SET; Schema: public; Owner: homestead
--

SELECT pg_catalog.setval('tree_binary_id_seq', 10, true);


--
-- TOC entry 2187 (class 0 OID 50222)
-- Dependencies: 189
-- Data for Name: tree_linear; Type: TABLE DATA; Schema: public; Owner: homestead
--

COPY tree_linear (id, user_id, parent_id, lft, rgt, depth, info, created_at, updated_at) FROM stdin;
1	1	0	1	2	0	\N	2016-06-23 15:26:06	\N
\.


--
-- TOC entry 2220 (class 0 OID 0)
-- Dependencies: 188
-- Name: tree_linear_id_seq; Type: SEQUENCE SET; Schema: public; Owner: homestead
--

SELECT pg_catalog.setval('tree_linear_id_seq', 1, false);


--
-- TOC entry 2192 (class 0 OID 65754)
-- Dependencies: 194
-- Data for Name: tree_ternary; Type: TABLE DATA; Schema: public; Owner: homestead
--

COPY tree_ternary (user_id, parent_id, created_at, t_position, level_abs, l, m, r, position_on_level, path_to_root) FROM stdin;
1	0	2016-06-29 10:52:55.34856	M	0	2	3	4	1	1
2	1	2016-06-29 16:51:37.375614	L	1	5	6	7	1	2,1
9	3	2016-06-29 17:04:02.652034	M	2	9	9	9	5	9,3,1
10	3	2016-06-29 17:04:04.442127	R	2	10	10	10	6	10,3,1
3	1	2016-06-29 16:51:48.115372	M	1	8	9	10	2	3,1
11	4	2016-06-29 17:04:28.567215	L	2	11	11	11	7	11,4,1
12	4	2016-06-29 17:04:30.431371	M	2	12	12	12	8	12,4,1
13	4	2016-06-29 17:04:38.487529	R	2	13	13	13	9	13,4,1
4	1	2016-06-29 16:52:00.36259	R	1	11	12	13	3	4,1
15	5	2016-06-29 17:08:34.759935	L	3	15	15	15	1	15,5,2,1
19	5	2016-06-29 17:08:39.799538	M	3	19	19	19	2	19,5,2,1
21	5	2016-06-29 17:08:42.835523	R	3	21	21	21	3	21,5,2,1
5	2	2016-06-29 17:02:55.793637	L	2	15	19	21	1	5,2,1
22	6	2016-06-29 17:08:45.2092	L	3	22	22	22	4	22,6,2,1
24	6	2016-06-29 17:08:47.06522	M	3	24	24	24	5	24,6,2,1
27	6	2016-06-29 17:08:49.299359	R	3	27	27	27	6	27,6,2,1
6	2	2016-06-29 17:03:22.698471	M	2	22	24	27	2	6,2,1
29	7	2016-06-29 17:08:52.330385	L	3	29	29	29	7	29,7,2,1
30	7	2016-06-29 17:09:02.918276	M	3	30	30	30	8	30,7,2,1
31	7	2016-06-29 17:09:05.83871	R	3	31	31	31	9	31,7,2,1
7	2	2016-06-29 17:03:36.862366	R	2	29	30	31	3	7,2,1
32	8	2016-06-29 17:53:58.159849	L	3	32	32	32	10	32,8,3,1
8	3	2016-06-29 17:04:00.172203	L	2	32	8	8	4	8,3,1
\.


--
-- TOC entry 2174 (class 0 OID 50135)
-- Dependencies: 176
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: homestead
--

COPY users (id, name, email, password, remember_token, created_at, updated_at) FROM stdin;
1	Shirley	top@btcc.vgt	$2y$10$uTOCODyzjQo6cFed/fFGIeZXuG80EUTEAeF5CNC8CGTZatqWhODgm	\N	2016-06-22 02:41:43	2016-06-22 02:41:43
2	Magali	akozey@dicki.net	$2y$10$JC9.GwvcWDOGRTna3TM2C.ayeE9Bz7I7y1yF9tv35NkFR15.dzMd2	\N	2016-06-22 02:41:43	2016-06-22 02:41:43
3	Scarlett	arnaldo61@hotmail.com	$2y$10$6Vb7eTh.SkuWasUGyuHzLuSSH0GoFx5Tik9msO73z/26Nz8qnhGz2	\N	2016-06-22 02:41:43	2016-06-22 02:41:43
4	Holly	rita.dickens@jenkins.org	$2y$10$zO0VVqafVA2tTbyQEuyLtufOlIMC8Js3dwE4QV6oGS/4OiumygsjO	\N	2016-06-22 02:41:43	2016-06-22 02:41:43
5	Larissa	quigley.marge@hotmail.com	$2y$10$Pqt71bVPMLSVIJTrhYXBt.IRnc2CIyfQx14k2s7OxtOFZDmouPi6K	\N	2016-06-22 02:41:43	2016-06-22 02:41:43
6	Abbey	mmoore@gmail.com	$2y$10$k5IENtJpjTFZzMm5dRDpT.vC1YwdwakSMYCcFo8XIHUKZ3FyZln3S	\N	2016-06-22 02:41:43	2016-06-22 02:41:43
7	Jennie	samir98@prosacco.com	$2y$10$dGRSR9pns4TeZrrF7oVD9OC4ZjvHxWQwGhAvtXY.bc22g53QCtFme	\N	2016-06-22 02:41:43	2016-06-22 02:41:43
8	Shawna	ernestine35@yahoo.com	$2y$10$4WuOvznzkvYXJIk96WVKJe8eOtEdhX/mPHiz5CsEHww7ObGb0YQka	\N	2016-06-22 02:41:43	2016-06-22 02:41:43
9	River	htillman@gmail.com	$2y$10$l2apG5fsrlsirO4JT7nb3.AxxtT3US8B/OFruBPQgK.lBOnqqDhQq	\N	2016-06-22 02:41:44	2016-06-22 02:41:44
10	Katherine	bgerhold@heathcote.net	$2y$10$82s1KBM1oNgO2WDjNA9vueH21AlOp6cFhhAC3gOd97XQ47r2AgFfu	\N	2016-06-22 02:41:44	2016-06-22 02:41:44
11	Treva	emerald.bergnaum@yahoo.com	$2y$10$E5y7yJVg9/jxkp.Zx4aTOeikTK1rcqik5vM8p8eupo9cpghD7sV0u	\N	2016-06-22 02:41:44	2016-06-22 02:41:44
\.


--
-- TOC entry 2221 (class 0 OID 0)
-- Dependencies: 175
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: homestead
--

SELECT pg_catalog.setval('users_id_seq', 11, true);


--
-- TOC entry 2185 (class 0 OID 50206)
-- Dependencies: 187
-- Data for Name: users_transactions; Type: TABLE DATA; Schema: public; Owner: homestead
--

COPY users_transactions (id, user_id, amount, type, status, sender_id, reciever_id, debit_flag, credit_flag, comment, hash, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 2222 (class 0 OID 0)
-- Dependencies: 186
-- Name: users_transactions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: homestead
--

SELECT pg_catalog.setval('users_transactions_id_seq', 1, false);


--
-- TOC entry 2177 (class 0 OID 50156)
-- Dependencies: 179
-- Data for Name: users_wallets; Type: TABLE DATA; Schema: public; Owner: homestead
--

COPY users_wallets (id, user_id, type, comment, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 2223 (class 0 OID 0)
-- Dependencies: 178
-- Name: users_wallets_id_seq; Type: SEQUENCE SET; Schema: public; Owner: homestead
--

SELECT pg_catalog.setval('users_wallets_id_seq', 1, false);


--
-- TOC entry 2030 (class 2606 OID 50170)
-- Name: cache_key_unique; Type: CONSTRAINT; Schema: public; Owner: homestead
--

ALTER TABLE ONLY cache
    ADD CONSTRAINT cache_key_unique UNIQUE (key);


--
-- TOC entry 2032 (class 2606 OID 50183)
-- Name: packages_name_unique; Type: CONSTRAINT; Schema: public; Owner: homestead
--

ALTER TABLE ONLY packages
    ADD CONSTRAINT packages_name_unique UNIQUE (name);


--
-- TOC entry 2034 (class 2606 OID 50181)
-- Name: packages_pkey; Type: CONSTRAINT; Schema: public; Owner: homestead
--

ALTER TABLE ONLY packages
    ADD CONSTRAINT packages_pkey PRIMARY KEY (id);


--
-- TOC entry 2038 (class 2606 OID 50202)
-- Name: profiles_pkey; Type: CONSTRAINT; Schema: public; Owner: homestead
--

ALTER TABLE ONLY profiles
    ADD CONSTRAINT profiles_pkey PRIMARY KEY (id);


--
-- TOC entry 2036 (class 2606 OID 50191)
-- Name: sessions_id_unique; Type: CONSTRAINT; Schema: public; Owner: homestead
--

ALTER TABLE ONLY sessions
    ADD CONSTRAINT sessions_id_unique UNIQUE (id);


--
-- TOC entry 2055 (class 2606 OID 50271)
-- Name: support_tickets_pkey; Type: CONSTRAINT; Schema: public; Owner: homestead
--

ALTER TABLE ONLY support_tickets
    ADD CONSTRAINT support_tickets_pkey PRIMARY KEY (id);


--
-- TOC entry 2050 (class 2606 OID 50252)
-- Name: tree_binary_parent_id_child_id_unique; Type: CONSTRAINT; Schema: public; Owner: homestead
--

ALTER TABLE ONLY tree_binary
    ADD CONSTRAINT tree_binary_parent_id_child_id_unique UNIQUE (parent_id, child_id);


--
-- TOC entry 2053 (class 2606 OID 50250)
-- Name: tree_binary_pkey; Type: CONSTRAINT; Schema: public; Owner: homestead
--

ALTER TABLE ONLY tree_binary
    ADD CONSTRAINT tree_binary_pkey PRIMARY KEY (id);


--
-- TOC entry 2045 (class 2606 OID 50230)
-- Name: tree_linear_pkey; Type: CONSTRAINT; Schema: public; Owner: homestead
--

ALTER TABLE ONLY tree_linear
    ADD CONSTRAINT tree_linear_pkey PRIMARY KEY (id);


--
-- TOC entry 2059 (class 2606 OID 65763)
-- Name: tree_ternary_pkey; Type: CONSTRAINT; Schema: public; Owner: homestead
--

ALTER TABLE ONLY tree_ternary
    ADD CONSTRAINT tree_ternary_pkey PRIMARY KEY (user_id, parent_id);


--
-- TOC entry 2021 (class 2606 OID 50145)
-- Name: users_email_unique; Type: CONSTRAINT; Schema: public; Owner: homestead
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- TOC entry 2023 (class 2606 OID 50143)
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: homestead
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 2041 (class 2606 OID 50214)
-- Name: users_transactions_pkey; Type: CONSTRAINT; Schema: public; Owner: homestead
--

ALTER TABLE ONLY users_transactions
    ADD CONSTRAINT users_transactions_pkey PRIMARY KEY (id);


--
-- TOC entry 2027 (class 2606 OID 50161)
-- Name: users_wallets_pkey; Type: CONSTRAINT; Schema: public; Owner: homestead
--

ALTER TABLE ONLY users_wallets
    ADD CONSTRAINT users_wallets_pkey PRIMARY KEY (id);


--
-- TOC entry 2056 (class 1259 OID 65765)
-- Name: idx_parent_child_copy; Type: INDEX; Schema: public; Owner: homestead
--

CREATE UNIQUE INDEX idx_parent_child_copy ON tree_ternary USING btree (user_id, parent_id);


--
-- TOC entry 2024 (class 1259 OID 50152)
-- Name: password_resets_email_index; Type: INDEX; Schema: public; Owner: homestead
--

CREATE INDEX password_resets_email_index ON password_resets USING btree (email);


--
-- TOC entry 2025 (class 1259 OID 50153)
-- Name: password_resets_token_index; Type: INDEX; Schema: public; Owner: homestead
--

CREATE INDEX password_resets_token_index ON password_resets USING btree (token);


--
-- TOC entry 2039 (class 1259 OID 50203)
-- Name: profiles_user_id_index; Type: INDEX; Schema: public; Owner: homestead
--

CREATE INDEX profiles_user_id_index ON profiles USING btree (user_id);


--
-- TOC entry 2057 (class 1259 OID 65764)
-- Name: ter_exclusive; Type: INDEX; Schema: public; Owner: homestead
--

CREATE UNIQUE INDEX ter_exclusive ON tree_ternary USING btree (user_id, parent_id, t_position);


--
-- TOC entry 2048 (class 1259 OID 50256)
-- Name: tree_binary_parent_id_child_id_index; Type: INDEX; Schema: public; Owner: homestead
--

CREATE INDEX tree_binary_parent_id_child_id_index ON tree_binary USING btree (parent_id, child_id);


--
-- TOC entry 2051 (class 1259 OID 50255)
-- Name: tree_binary_parent_id_index; Type: INDEX; Schema: public; Owner: homestead
--

CREATE INDEX tree_binary_parent_id_index ON tree_binary USING btree (parent_id);


--
-- TOC entry 2042 (class 1259 OID 50233)
-- Name: tree_linear_lft_index; Type: INDEX; Schema: public; Owner: homestead
--

CREATE INDEX tree_linear_lft_index ON tree_linear USING btree (lft);


--
-- TOC entry 2043 (class 1259 OID 50232)
-- Name: tree_linear_parent_id_index; Type: INDEX; Schema: public; Owner: homestead
--

CREATE INDEX tree_linear_parent_id_index ON tree_linear USING btree (parent_id);


--
-- TOC entry 2046 (class 1259 OID 50234)
-- Name: tree_linear_rgt_index; Type: INDEX; Schema: public; Owner: homestead
--

CREATE INDEX tree_linear_rgt_index ON tree_linear USING btree (rgt);


--
-- TOC entry 2047 (class 1259 OID 50231)
-- Name: tree_linear_user_id_index; Type: INDEX; Schema: public; Owner: homestead
--

CREATE INDEX tree_linear_user_id_index ON tree_linear USING btree (user_id);


--
-- TOC entry 2060 (class 1259 OID 65766)
-- Name: user_id; Type: INDEX; Schema: public; Owner: homestead
--

CREATE UNIQUE INDEX user_id ON tree_ternary USING btree (user_id);


--
-- TOC entry 2028 (class 1259 OID 50162)
-- Name: users_wallets_user_id_index; Type: INDEX; Schema: public; Owner: homestead
--

CREATE INDEX users_wallets_user_id_index ON users_wallets USING btree (user_id);


--
-- TOC entry 2062 (class 2620 OID 65569)
-- Name: t_ternary_insert_tree_linear_trg; Type: TRIGGER; Schema: public; Owner: homestead
--

CREATE TRIGGER t_ternary_insert_tree_linear_trg AFTER INSERT OR UPDATE ON tree_linear FOR EACH ROW EXECUTE PROCEDURE ternary_trigger_handler_add_matrix_relation();


--
-- TOC entry 2061 (class 2606 OID 50215)
-- Name: users_transactions_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: homestead
--

ALTER TABLE ONLY users_transactions
    ADD CONSTRAINT users_transactions_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 2199 (class 0 OID 0)
-- Dependencies: 5
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2016-06-30 00:27:52

--
-- PostgreSQL database dump complete
--


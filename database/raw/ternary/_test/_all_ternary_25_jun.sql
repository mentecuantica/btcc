--create simple function
--make it a trigger


DROP FUNCTION tt_add_to_matrix( INTEGER, INTEGER );


CREATE OR REPLACE FUNCTION public.load_matrix(pid INT)
  RETURNS TABLE( user_id INT, parent_level INT,
                 childs JSONB,  positions JSONB,
                 positions_array e_ternary_position[], child_id INTEGER, is_leaf BOOL,  child_level INTEGER)
AS
$$
BEGIN
  RETURN QUERY SELECT
                 t1.child_id                               AS user_id,
                 t1.abs_level                               AS parent_level,
                 json_agg(t2.child_id)
                 OVER (PARTITION BY t1.parent_id) :: JSONB AS childs,
                 json_agg(t2.t_position)
                 OVER (PARTITION BY t1.parent_id) :: JSONB AS positions,
                 array_agg(t2.t_position)
                 OVER (PARTITION BY t1.parent_id)          AS positions_array,
                 t2.child_id                               AS child_id,
                 t2.t_position,
                 CASE WHEN t2.child_id ISNULL
                   THEN TRUE
                 ELSE FALSE END                            AS is_leaf,
                 CASE WHEN t2.t_position = 'L'
                   THEN t2.child_id
                 ELSE NULL END                             AS "l",

                 t2.abs_level                               AS child_level
               FROM tree_ternary t1 LEFT OUTER JOIN tree_ternary t2 ON (t1.child_id = t2.parent_id)
               WHERE t1.child_id = pid;
END
$$
LANGUAGE 'plpgsql' STABLE;


CREATE OR REPLACE FUNCTION public.tt_add_to_matrix(pid INT, new_uid INT)
  RETURNS INT
AS
$BODY$
DECLARE
  is_parent_exists  BOOL :=FALSE;
  is_free_place     BOOL :=FALSE;

  p                 RECORD;

  existing_children INTEGER ARRAY;
  children_count    INTEGER;
  childs            JSONB;
  filled_positions  JSONB;
  filled_pos_arr    CHARACTER [];
  mix               JSONB;
  I                 INTEGER :=0;
  numtimes          INTEGER :=3;
  new_node_id       INTEGER;
  new_position      E_TERNARY_POSITION;
  new_depth         INTEGER;
  old_pos           CHARACTER;
  no_places         BOOLEAN :=TRUE;
BEGIN

  SELECT * INTO p FROM load_matrix(pid);

  IF (NOT FOUND)
  THEN
    RETURN -1;
  END IF;
  RAISE NOTICE 'Parent found: %, level: %, is leaf: %', FOUND, p.parent_level, p.is_leaf;

  childs:=p.childs;
  filled_positions:=p.positions;
  filled_pos_arr:=p.positions_array;
  RAISE NOTICE 'Parent has childs %', jsonb_array_length(childs);
  RAISE NOTICE 'Parent has childs array %', array_to_string(filled_pos_arr, ':', '*');

  IF p.is_leaf
  THEN
    filled_pos_arr:=ARRAY[]::CHARACTER[];

  END IF;


  SELECT unnest(ARRAY['L','R','M']::CHARACTER[])
  EXCEPT
  SELECT unnest(filled_pos_arr::CHARACTER[])
  LIMIT 1 INTO new_position;


  IF NOT FOUND
  THEN
    RAISE WARNING 'Positions not exists ';
    --- CALL SEARCH FREE
  ELSE
    IF new_position IS NOT NULL
    THEN
      INSERT INTO tree_ternary (parent_id, child_id, t_position, abs_level)
      VALUES (pid, new_uid, new_position, p.parent_level + 1)
      RETURNING id
        INTO new_node_id;
      RETURN new_node_id;
    END IF;
  END IF;

  RETURN -1;
END;

$BODY$ LANGUAGE 'plpgsql';







--LITTLE TEST
SELECT * FROM ternary_add_to_parent(15, 19);

-- parent can be only as child in a table
SELECT
  t1.child_id                               AS user_id,
  t1.abs_level                               AS parent_level,
  json_agg(t2.child_id)
  OVER (PARTITION BY t1.parent_id) :: JSONB AS childs,
  json_agg(t2.t_position)
  OVER (PARTITION BY t1.parent_id) :: JSONB AS positions,
  array_agg(t2.t_position)
  OVER (PARTITION BY t1.parent_id) AS positions_array,
  t2.child_id                               AS child_id,
  t2.t_position,
  CASE WHEN t2.child_id ISNULL
    THEN TRUE
  ELSE FALSE END                            AS is_free,
  ROW (CASE WHEN t2.t_position = 'L'
    THEN t2.child_id
       ELSE NULL END,
  CASE WHEN t2.t_position = 'M'
    THEN t2.child_id
  ELSE NULL END,
  CASE WHEN t2.t_position = 'R'
    THEN t2.child_id
  ELSE NULL END)                            AS lmr,

  t2.abs_level                               AS child_level

FROM tree_ternary t1 LEFT OUTER JOIN tree_ternary t2 ON (t1.child_id = t2.parent_id)
WHERE t1.child_id = 4;

---TRIGGER TEST
INSERT INTO public.tree_linear VALUES (1,	1,	0,	1,	2,	0,	NULL	,'2016-06-23 15:26:06');



--- GET TREE
WITH RECURSIVE child_nodes AS (

  SELECT parent_id, user_id, t_position, 0 as depth,path_to_root FROM tree_ternary WHERE parent_id = 0


  UNION

  SELECT tt.parent_id, tt.user_id, tt.t_position, cn.depth + 1 t_lev, tt.path_to_root
  FROM child_nodes cn, tree_ternary as tt
  WHERE tt.parent_id = cn.user_id

)
SELECT depth, parent_id, array_agg(t_position) as pos,
  array_length(array_agg(user_id),1) as children,
  array_agg(user_id) as children_ids  -- OVER mywindow
FROM child_nodes
GROUP BY parent_id, depth ORDER BY DEPTH;

/* FOREACH old_pos IN ARRAY ARRAY ['L', 'M', 'R'] :: CHARACTER [] LOOP

    IF old_pos IN (filled_pos_arr)
    THEN
      RAISE NOTICE 'Place: % is occupied', old_pos;

    ELSE
      no_places:=FALSE;
      new_position:=old_pos;
    END IF;

  END LOOP;*/

---ALL(filled_pos_arr)
-- IF (filled_positions ?| ARRAY ['R', 'M', 'L'])
--THEN
/*RAISE NOTICE 'Child exists %', filled_positions -> 0;
IF (filled_positions -> 0 = 'L')
THEN
  new_position:= 'M';
END IF;
IF (filled_positions -> 1 = 'M')
THEN
  new_position:= 'R';
END IF;
IF (filled_positions -> 2 = 'R')
THEN
  new_position:= NULL ;
END IF;*/

DO $_$
DECLARE
  r t_matrix_node_simple;
  n tree_ternary%ROWTYPE;
BEGIN
  SELECT * INTO r FROM ternary_load_matrix_node_simple(10);

  RAISE NOTICE 'FUCK FU %',r.root_path_array;

END; $_$;

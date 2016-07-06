DROP FUNCTION IF EXISTS public.ternary_get_nodes_with_places_by_level(absoluteLevel INTEGER );

CREATE OR REPLACE FUNCTION public.ternary_get_nodes_with_places_by_level(absoluteLevel INTEGER)
  RETURNS TABLE(user_id INTEGER,
                parent_id INTEGER,
                created_at TIMESTAMP,
                level_abs INTEGER,
                position_on_level INTEGER,
                root_path_array INTEGER[],
                children_positions INTEGER[],
                is_leaf         BOOL,
                free_places BOOLEAN[])
AS
$BODY$

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
$BODY$ LANGUAGE SQL;


DROP FUNCTION IF EXISTS public.ternary_insert_new_node(pNode t_matrix_node_simple, nNode tree_ternary );

CREATE OR REPLACE FUNCTION public.ternary_insert_new_node(pNode t_matrix_node_simple, nNode tree_ternary)
  RETURNS INTEGER
AS
$BODY$
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

$BODY$ LANGUAGE 'plpgsql';





DROP FUNCTION IF EXISTS public.ternary_load_matrix_node_simple(pid INTEGER );
CREATE OR REPLACE FUNCTION public.ternary_load_matrix_node_simple(pid INTEGER)
  RETURNS SETOF T_MATRIX_NODE_SIMPLE
AS
$$
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
$$
LANGUAGE 'plpgsql';




DROP FUNCTION IF EXISTS public.ternary_add_to_parent(parentId INTEGER, newChildId INTEGER );

CREATE OR REPLACE FUNCTION public.ternary_add_to_parent(parentId INTEGER, newChildId INTEGER)
  RETURNS INTEGER
AS
$BODY$
DECLARE
  is_free_place     BOOL :=FALSE;

  node                 t_matrix_node_simple;
  next_node       RECORD;
  existing_children INTEGER ARRAY;
  children_count    INTEGER;
  filled_pos_arr    CHARACTER [];
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

$BODY$ LANGUAGE 'plpgsql';



DROP FUNCTION IF EXISTS public.ternary_get_ancestors(nodeId INTEGER );
CREATE OR REPLACE FUNCTION public.ternary_get_ancestors(nodeId INTEGER)
  RETURNS TABLE(id INTEGER)
  -- returns TABLE of ancestors ID
AS
$BODY$
DECLARE
  var_temp  BOOL :=FALSE;
BEGIN
  RETURN QUERY

  SELECT * FROM  (SELECT regexp_split_to_table(tt.path_to_root,',')::INTEGER as id
                  FROM tree_ternary tt WHERE user_id = nodeId) as all_ancestors

  WHERE all_ancestors.id <> nodeId;

END

$BODY$ LANGUAGE 'plpgsql';


DROP FUNCTION IF EXISTS public.ternary_get_ancestors_array(nodeId INTEGER );
CREATE OR REPLACE FUNCTION public.ternary_get_ancestors_array(nodeId INTEGER)
  RETURNS INTEGER[]
AS
$BODY$
DECLARE
  var_temp  BOOL :=FALSE;
BEGIN
  --SELECT unnest(t) as id FROM ternary_get_ancestors_array(7) as t

  RETURN array_remove(string_to_array(tt.path_to_root,',')::INTEGER[],nodeId)
  FROM tree_ternary tt WHERE user_id = nodeId;


END

$BODY$ LANGUAGE 'plpgsql';

DROP FUNCTION IF EXISTS ternary_get_descendants(parentId INTEGER, maxLevel INTEGER);

CREATE OR REPLACE FUNCTION ternary_get_descendants(parentId INTEGER, maxLevel INTEGER DEFAULT 3)
  RETURNS TABLE(parent_id int , user_id int,  t_position e_ternary_position, depth int, level_rel int,path_to_root VARCHAR) AS $$
DECLARE
  parent_level INTEGER;
BEGIN

  SELECT
    tt.level_abs INTO parent_level
  FROM tree_ternary tt
  WHERE tt.parent_id = parentId;

  RAISE NOTICE 'Parent depth level is %', parent_level;


  maxLevel:=maxLevel + parent_level;

  RAISE NOTICE 'Relative max depth level is %', maxLevel;


  RETURN QUERY
  WITH RECURSIVE child_nodes AS (
    (
      SELECT
        tt.*, 1 as auto_level
      FROM tree_ternary tt
      WHERE tt.user_id = parentId
    )
    UNION
    SELECT
      tt.*, cn.auto_level+1 as auto_level
    FROM child_nodes cn, tree_ternary tt
    WHERE tt.parent_id = cn.user_id

  ) SELECT
      cn.parent_id, cn.user_id, cn.t_position, cn.level_abs as depth, auto_level as level_rel,cn.path_to_root
    FROM child_nodes cn WHERE auto_level <= maxLevel;
END;
$$ LANGUAGE 'plpgsql';





--Trigger function
DROP FUNCTION IF EXISTS ternary_trigger_handler_add_matrix_relation();
CREATE OR REPLACE FUNCTION public.ternary_trigger_handler_add_matrix_relation()
  RETURNS trigger AS
$__$
DECLARE
  zz INTEGER:=NULL ;
BEGIN
  IF TG_OP = 'INSERT' THEN
    RAISE WARNING 'We have inserted the linear user %',NEW.user_id;
  ELSEIF TG_OP = 'UPDATE' THEN


    RAISE WARNING 'T: old pid %, new %pid',OLD.parent_id,NEW.parent_id;

    IF ( (OLD.parent_id IS NULL AND new.parent_id IS NOT NULL ) AND old.user_id<>1 ) THEN
        RAISE WARNING 'T: new.lft %, new.rgt %',new.lft,new.rgt;
        SELECT * INTO zz FROM ternary_add_to_parent(new.parent_id, old.user_id);
    END IF;


    --CHECK THAT user_id has no parent id, and depth = 0 and left-rgt = 1
    --and parent_id supplied
    -- after call tt_add_to_matrix(user_id, parent_id)
    RAISE WARNING 'TRG: We have updated the linear user %',NEW.user_id;
  END IF;

  RETURN new;
END ;
$__$ language plpgsql;

--Trigger on update or insert tree linear
--DROP TRIGGER IF EXISTS ternary_insert_tree_linear_trg;

CREATE TRIGGER t_ternary_insert_tree_linear_trg
AFTER INSERT OR UPDATE ON tree_linear
FOR EACH ROW
EXECUTE PROCEDURE ternary_trigger_handler_add_matrix_relation();
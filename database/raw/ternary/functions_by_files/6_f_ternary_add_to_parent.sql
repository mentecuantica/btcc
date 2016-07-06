

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
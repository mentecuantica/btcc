

--- FUNCTIONS

CREATE OR REPLACE FUNCTION public.bt_add_to_parent(new_node_parent_id INT8, new_position e_binary_position,
                                                   user_id       INT8)
  RETURNS INT
AS
$BODY$
DECLARE
  is_root_exists    BOOL DEFAULT FALSE;

  is_parent_exists  BOOL DEFAULT FALSE;
  is_free_place     BOOL DEFAULT FALSE;

  existing_children INTEGER ARRAY;
  children_count    INTEGER;
  json_result JSON;
  new_node_id INTEGER;
  new_depth INTEGER;
BEGIN

  SELECT EXISTS(SELECT 1
                FROM tree_binary
                WHERE parent_id=0 AND bt_position = 'N') INTO is_root_exists;


  IF is_root_exists IS FALSE
  THEN
    RAISE EXCEPTION 'No root in the table please use bt_add_root()';

    RETURN FALSE ;

  END IF;

  RAISE NOTICE 'Root found';

  --check if parent exitst
  -- тот к кому мы хотим добавить, он должен быть обязательно хотя бы раз встречаться в поле child_id
  SELECT EXISTS(SELECT 1
                FROM tree_binary
                WHERE child_id = new_node_parent_id)
  INTO is_parent_exists;

  IF is_parent_exists IS FALSE
  THEN
    RAISE EXCEPTION 'Nonexistent ID --> %', new_node_parent_id
    USING HINT = 'Please check your PARENT ID';
  ELSE
    RAISE NOTICE 'Parent with id:% exists', new_node_parent_id;


    /*SELECT json_object_agg(p,json_build_array(child_id,depth,parent_id)) INTO json_result
    FROM tree_binary
    WHERE parent_id = new_node_parent_id;*/

    --SELECT json_result::jsonb <@ new_position INTO is_free_place;

    SELECT depth+1 INTO new_depth FROM tree_binary WHERE child_id = new_node_parent_id;

    SELECT array_agg(child_id)
    INTO existing_children
    FROM tree_binary
    WHERE parent_id = new_node_parent_id;



    IF existing_children IS NULL THEN
      INSERT INTO tree_binary (parent_id, child_id, bt_position, depth)
      VALUES (new_node_parent_id, user_id, new_position,new_depth) RETURNING id INTO new_node_id;
      RETURN new_node_id;
    END IF;


    children_count = array_length(existing_children,1);

    CASE children_count
      WHEN 2
      THEN
        RAISE WARNING 'No place with parent %', new_node_parent_id;
      WHEN 1
      THEN
        RAISE NOTICE 'One place with parent %', new_node_parent_id;
    ELSE
      RAISE NOTICE 'SMTH WICKED';

    END CASE;

    -- проверка на свободную ячейку
    SELECT NOT EXISTS (SELECT 1 FROM tree_binary
    WHERE parent_id = new_node_parent_id AND bt_position = new_position) INTO is_free_place;

    -- подсчет детей
    /*SELECT COUNT(child_id)
    INTO is_free_place
    FROM tree_binary
    WHERE parent_id = new_node_parent_id AND p = new_position;*/

    IF is_free_place IS TRUE
    THEN
      RAISE NOTICE 'Place is free';
      INSERT INTO tree_binary (parent_id, child_id, bt_position)
      VALUES (new_node_parent_id, user_id, new_position) RETURNING id INTO new_node_id;
      RETURN new_node_id;

    ELSE
      RAISE WARNING 'No place ';
      RETURN -1;
    END IF;


  END IF;


  RETURN 0;
END
$BODY$
LANGUAGE plpgsql VOLATILE;




CREATE OR REPLACE FUNCTION bt_get_ancestors(item_id INTEGER)
  RETURNS TABLE(ancestor int, bt_position e_binary_position) AS $$
DECLARE

  some_number INTEGER;
BEGIN

  RETURN QUERY
  WITH RECURSIVE child_nodes AS (
    SELECT
      child_id,
      ARRAY[]::integer[] AS ancestors,
      ARRAY[]::e_binary_position[] AS positions
    FROM tree_binary
    WHERE parent_id = 0 AND tree_binary.bt_position='N' --todo depends on INDEXES, UNIQUES, PK

    UNION ALL

    SELECT
      bt.child_id,
      cn.ancestors || bt.parent_id,
      cn.positions || bt.bt_position
    FROM tree_binary bt, child_nodes cn
    WHERE bt.parent_id = cn.child_id
  ) SELECT unnest(ancestors) AS ancestor,unnest(positions) AS position
    FROM child_nodes
    WHERE child_id = item_id;

END;
$$ LANGUAGE 'plpgsql';


CREATE OR REPLACE FUNCTION bt_get_descendants(parentId INTEGER, maxLevel INTEGER DEFAULT 3)
  RETURNS TABLE(id int,parent_id int , child_id int,  bt_position e_binary_position, depth int, level int) AS $$
DECLARE
  parent_level INTEGER;
BEGIN

  SELECT
    bt.depth INTO parent_level
  FROM tree_binary bt
  WHERE bt.parent_id = parentId;

  RAISE NOTICE 'Parent depth level is %', parent_level;


  maxLevel:=maxLevel + parent_level;

  RAISE NOTICE 'Relative max depth level is %', maxLevel;


  RETURN QUERY
  WITH RECURSIVE child_nodes AS (
    (
      SELECT
        bt.*, 1 as auto_level
      FROM tree_binary bt
      WHERE bt.parent_id = parentId
    )
    UNION
    SELECT
      bt.*, cn.auto_level+1 as auto_level
    FROM child_nodes cn, tree_binary bt
    WHERE bt.parent_id = cn.child_id

  ) SELECT
      cn.id, cn.parent_id, cn.child_id, cn.bt_position, cn.depth, auto_level
    FROM child_nodes cn WHERE auto_level <= maxLevel;
END;
$$ LANGUAGE 'plpgsql';

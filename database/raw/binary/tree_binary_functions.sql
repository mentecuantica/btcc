--- FUNCTIONS

CREATE OR REPLACE FUNCTION public.bt_add_to_parent(new_node_parent_id INTEGER, new_position e_binary_position,
                                                   new_user_id        INTEGER)
  RETURNS INT
AS
$BODY$
DECLARE
  is_root_exists    BOOL :=FALSE;
  is_parent_exists  BOOL :=FALSE;
  is_free_place     BOOL :=FALSE;

  existing_children INTEGER ARRAY;
  children_count    INTEGER;
  json_result       JSON;
  new_node_id       INTEGER;
  new_depth         INTEGER;
BEGIN

  SELECT EXISTS(SELECT 1
                FROM tree_binary
                WHERE parent_id = 0 AND bt_position = 'N')
  INTO is_root_exists;


  IF is_root_exists IS FALSE
  THEN
    RAISE EXCEPTION 'No root in the table please use bt_add_root()';
    RETURN -1;
  END IF;

  --check if parent exitst
  -- тот к кому мы хотим добавить, он должен быть обязательно хотя бы раз встречаться в поле user_id
  SELECT EXISTS(SELECT 1
                FROM tree_binary
                WHERE user_id = new_node_parent_id)
  INTO is_parent_exists;

  IF is_parent_exists IS FALSE
  THEN
    RAISE EXCEPTION 'Nonexistent ID --> %', new_node_parent_id
    USING HINT = 'Please check your PARENT ID';
    RETURN -1;
  END IF;

  --@todo Change depth algorithn
  SELECT depth + 1
  INTO new_depth
  FROM tree_binary
  WHERE user_id = new_node_parent_id;

  -- проверка на свободную ячейку
  SELECT *
  FROM bt_is_free_position(new_node_parent_id, new_position)
  INTO is_free_place;

  IF is_free_place
  THEN
    iNSERT INTO tree_binary (parent_id, user_id, bt_position,depth)
    VALUES (new_node_parent_id, new_user_id, new_position, new_depth)
    RETURNING user_id
    INTO new_node_id;
    RETURN new_node_id;
  END IF;



/*  SELECT array_agg(user_id)
  INTO existing_children
  FROM tree_binary
  WHERE parent_id = new_node_parent_id;


  IF existing_children IS NULL
  THEN
    INSERT INTO tree_binary (parent_id, user_id, bt_position, depth)
    VALUES (new_node_parent_id, new_user_id, new_position, new_depth)
    RETURNING user_id
      INTO new_node_id;
    RETURN new_node_id;
  END IF;*/

/*
  children_count = array_length(existing_children, 1);

  CASE children_count
    WHEN 2
    THEN
      RAISE WARNING 'No place with parent %', new_node_parent_id;
    WHEN 1
    THEN
      RAISE NOTICE 'One place with parent %', new_node_parent_id;
  ELSE
    RAISE NOTICE 'SMTH WICKED';

  END CASE;*/

  -- подсчет детей
  /*SELECT COUNT(user_id)
  INTO is_free_place
  FROM tree_binary
  WHERE parent_id = new_node_parent_id AND p = new_position;*/
  --
  --   IF NOT is_free_place THEN
  --     RETURN 0;
  --   END IF ;
  --
  --
  --   INSERT INTO tree_binary (parent_id, user_id, bt_position)
  --   VALUES (new_node_parent_id, new_user_id, new_position)
  --   RETURNING user_id
  --     INTO new_node_id;
  --   RETURN new_node_id;



  RETURN -1;
END
$BODY$
LANGUAGE plpgsql VOLATILE;


CREATE OR REPLACE FUNCTION bt_get_ancestors(item_id INTEGER)
  RETURNS TABLE(ancestor INT, bt_position e_binary_position) AS $$
DECLARE

  some_number INTEGER;
BEGIN

  RETURN QUERY
  WITH RECURSIVE child_nodes AS (
    SELECT
      user_id,
      ARRAY [] :: INTEGER []           AS ancestors,
      ARRAY [] :: e_binary_position [] AS positions
    FROM tree_binary
    WHERE parent_id = 0 AND tree_binary.bt_position = 'N' --todo depends on INDEXES, UNIQUES, PK

    UNION ALL

    SELECT
      bt.user_id,
      cn.ancestors || bt.parent_id,
      cn.positions || bt.bt_position
    FROM tree_binary bt, child_nodes cn
    WHERE bt.parent_id = cn.user_id
  ) SELECT
      unnest(ancestors) AS ancestor,
      unnest(positions) AS position
    FROM child_nodes
    WHERE user_id = item_id;

END;
$$ LANGUAGE 'plpgsql';


CREATE OR REPLACE FUNCTION bt_get_descendants(parentId INTEGER, maxLevel INTEGER DEFAULT 3)
  RETURNS TABLE(user_id INT, parent_id INT, bt_position e_binary_position, depth INT, level INT) AS $$
DECLARE
  parent_level INTEGER;
BEGIN

  SELECT bt.depth
  INTO parent_level
  FROM tree_binary bt
  WHERE bt.parent_id = parentId;

  RAISE NOTICE 'Parent depth level is %', parent_level;


  maxLevel:=maxLevel + parent_level;

  RAISE NOTICE 'Relative max depth level is %', maxLevel;


  RETURN QUERY
  WITH RECURSIVE child_nodes AS (
    (
      SELECT
        bt.*,
        1 AS auto_level
      FROM tree_binary bt
      WHERE bt.parent_id = parentId
    )
    UNION
    SELECT
      bt.*,
      cn.auto_level + 1 AS auto_level
    FROM child_nodes cn, tree_binary bt
    WHERE bt.parent_id = cn.user_id

  ) SELECT
      cn.user_id,
      cn.parent_id,
      cn.bt_position,
      cn.depth,
      auto_level
    FROM child_nodes cn
    WHERE auto_level <= maxLevel;
END;
$$ LANGUAGE 'plpgsql';


CREATE OR REPLACE FUNCTION bt_get_descendants_with_parent(parentId INTEGER, maxLevel INTEGER DEFAULT 3)
  RETURNS TABLE(user_id INT, parent_id INT, bt_position e_binary_position, depth INT, level INT) AS $$
DECLARE
  parent_level INTEGER;
BEGIN

  SELECT bt.depth
  INTO parent_level
  FROM tree_binary bt
  WHERE bt.parent_id = parentId;

  RAISE NOTICE 'Parent depth level is %', parent_level;


  maxLevel:=maxLevel + parent_level;

  RAISE NOTICE 'Relative max depth level is %', maxLevel;


  RETURN QUERY
  WITH RECURSIVE child_nodes AS (
    (
      SELECT
        bt.*,
        1 AS auto_level
      FROM tree_binary bt
      WHERE bt.user_id = parentId
    )
    UNION
    SELECT
      bt.*,
      cn.auto_level + 1 AS auto_level
    FROM child_nodes cn, tree_binary bt
    WHERE bt.parent_id = cn.user_id

  ) SELECT
      cn.user_id,
      cn.parent_id,
      cn.bt_position,
      cn.depth,
      auto_level
    FROM child_nodes cn
                    WHERE auto_level <= maxLevel;
END;
$$ LANGUAGE 'plpgsql';


CREATE OR REPLACE FUNCTION bt_is_free_position(new_node_parentId INTEGER, new_position e_binary_position)
  RETURNS BOOLEAN AS $$
DECLARE
  if_exists BOOLEAN;
BEGIN

  SELECT NOT EXISTS(SELECT 1
                    FROM tree_binary
                    WHERE parent_id = new_node_parentId AND bt_position = new_position)
  INTO if_exists;
  RETURN if_exists;
END;
$$ LANGUAGE 'plpgsql';


CREATE OR REPLACE FUNCTION bt_validate_position(bt_parent_id INTEGER, new_position e_binary_position)
  RETURNS TABLE(validation VARCHAR(10), result BOOLEAN) AS $$
BEGIN
  RETURN QUERY SELECT
                 unnest(ARRAY ['root_exists', 'parent_exists', 'free_place'] :: VARCHAR []) AS validation,
                 unnest(ARRAY [r, p, f])                                                    AS result
               FROM (

                      SELECT
                        EXISTS(SELECT 1
                               FROM tree_binary
                               WHERE parent_id = 0 AND bt_position = 'N') AS r, --check if ROOT exists
                        EXISTS(
                            SELECT 1
                            FROM
                              tree_binary
                            WHERE
                              user_id = bt_parent_id              --parent exists as user_id
                        )                                                 AS p,
                        NOT EXISTS(
                            SELECT 1
                            FROM
                              tree_binary
                            WHERE
                              parent_id = bt_parent_id        -- space if free bt_parent_id not a parent himself
                              AND bt_position = new_position
                        )                                                 AS f) AS t;
END;
$$ LANGUAGE 'plpgsql';


CREATE OR REPLACE FUNCTION bt_validate_position_json(bt_parent_id INTEGER, new_position e_binary_position)
  RETURNS JSON AS $$

DECLARE
  result JSON;

BEGIN

  RAISE NOTICE 'bt_v_p_j bt_parent_id = %s, new_pos = %s', bt_parent_id, new_position;

  /*SELECT json_object_agg(p,json_build_array(user_id,depth,parent_id)) INTO json_result
  FROM tree_binary
  WHERE parent_id = new_node_parent_id;*/

  --SELECT json_result::jsonb <@ new_position INTO is_free_place;

  SELECT json_object(ARRAY ['root_exists', 'parent_exists', 'free_place'] :: TEXT [],
                     ARRAY [r, p, f] :: TEXT []) AS validation_result --                                      AS result
  FROM (

         SELECT
           EXISTS(SELECT 1
                  FROM tree_binary
                  WHERE parent_id = 0 AND bt_position = 'N') AS r,
           EXISTS(
               SELECT 1
               FROM
                 tree_binary
               WHERE
                 user_id = bt_parent_id --new parent at this stage is still a user_id , afterware he will be parent
           )                                                 AS p,
           NOT EXISTS(
               SELECT 1
               FROM
                 tree_binary
               WHERE
                 parent_id = bt_parent_id
                 AND bt_position = new_position
           )                                                 AS f) AS t
  INTO result;
  RETURN result;
END;
$$ LANGUAGE 'plpgsql';
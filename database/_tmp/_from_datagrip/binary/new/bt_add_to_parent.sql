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
BEGIN
  --check if root exists
  /*  SELECT COUNT(*)
             FROM binary_tree
             WHERE parent_id IS NULL AND bt_position = 'N');*/

  SELECT EXISTS(SELECT 1
                FROM binary_tree
                WHERE parent_id IS NULL AND bt_position = 'N') INTO is_root_exists;


  IF is_root_exists IS FALSE
  THEN
    RAISE EXCEPTION 'No root in the table please use bt_add_root()';

    RETURN FALSE ;
    --INSERT INTO binary_tree (parent_id, child_id, bt_position, bt_level) VALUES (NULL ,new_node_parent_id,bt_position,0);

  END IF;

  RAISE NOTICE 'Root found';

  --check if parent exitst
  -- тот к кому мы хотим добавить, он должен быть обязательно хотя бы раз встречаться в поле child_id
  SELECT EXISTS(SELECT 1
                FROM binary_tree
                WHERE child_id = new_node_parent_id)
  INTO is_parent_exists;

  IF is_parent_exists IS FALSE
  THEN
   RAISE EXCEPTION 'Nonexistent ID --> %', new_node_parent_id
      USING HINT = 'Please check your PARENT ID';
  ELSE
    RAISE NOTICE 'Parent with id:% exists', new_node_parent_id;

    --INSERT INTO binary_tree (parent_id, child_id, bt_position, bt_level) VALUES (NULL ,new_node_parent_id,bt_position,0);

    --parent существует, дальше можно ли к нему добавить в нужное место


    --Случай один проверить есть ли у парента вообще дети
    --  Если вообще нет - то оба места свободны, можно добавлять
    --  Если парент есть в столбце parent_id то дети у него есть...

    /*SELECT json_object_agg(bt_position,json_build_array(child_id,bt_level,parent_id)) INTO json_result
    FROM binary_tree
    WHERE parent_id = new_node_parent_id;*/

    --SELECT json_result::jsonb <@ new_position INTO is_free_place;

    SELECT array_agg(child_id)
    INTO existing_children
    FROM binary_tree
    WHERE parent_id = new_node_parent_id;

    IF existing_children IS NULL THEN
        INSERT INTO binary_tree (parent_id, child_id, bt_position)
        VALUES (new_node_parent_id, user_id, new_position) RETURNING id INTO new_node_id;
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
    SELECT NOT EXISTS (SELECT 1 FROM binary_tree
    WHERE parent_id = new_node_parent_id AND bt_position = new_position) INTO is_free_place;

    -- подсчет детей
    /*SELECT COUNT(child_id)
    INTO is_free_place
    FROM binary_tree
    WHERE parent_id = new_node_parent_id AND bt_position = new_position;*/

    IF is_free_place IS TRUE
    THEN
      RAISE NOTICE 'Place is free';
       INSERT INTO binary_tree (parent_id, child_id, bt_position)
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


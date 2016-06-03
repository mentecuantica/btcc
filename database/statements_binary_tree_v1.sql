-- таблица на всякие случаи хранения бинарного
CREATE TYPE e_binary_position AS ENUM (
  'L',
  'R',
  'N');

CREATE TABLE public.binary_tree (
  id SERIAL NOT NULL ,
  --user_id int, -- по сути он и есть parent_id
	parent_id int NULL,
	child_id int NOT NULL,
	bt_position e_binary_position NOT NULL,
	l_child_id int NULL ,
	r_child_id int NULL ,
	refer_id int NULL ,
	bt_level int NULL ,
  info TEXT,
	PRIMARY KEY (parent_id, child_id),
	UNIQUE(parent_id, child_id),
	UNIQUE(parent_id, child_id, bt_position)

	--   parent_id INTEGER REFERENCES binary_tree(parent_id) ???

);

CREATE INDEX "idx_parent_id" ON "public"."binary_tree" ("parent_id");
CREATE INDEX "idx_parent_child" ON "public"."binary_tree" ("parent_id","child_id");

-- ROOT
INSERT INTO public.binary_tree ( parent_id, child_id, bt_position, refer_id, bt_level) VALUES ( 0 , 1, 'N', null, 0);

--- OTHERS
INSERT INTO public.binary_tree ( parent_id, child_id, bt_position, refer_id, bt_level) VALUES ( 1, 2, 'L', null, 1);
INSERT INTO public.binary_tree ( parent_id, child_id, bt_position, refer_id, bt_level) VALUES ( 1, 3, 'R', null, 1);
INSERT INTO public.binary_tree ( parent_id, child_id, bt_position, refer_id, bt_level) VALUES ( 2, 14, 'L', null, 2);
INSERT INTO public.binary_tree ( parent_id, child_id, bt_position, refer_id, bt_level) VALUES ( 2, 15, 'R', null, 2);
INSERT INTO public.binary_tree ( parent_id, child_id, bt_position, refer_id, bt_level) VALUES ( 3, 16, 'R', null, 2);
INSERT INTO public.binary_tree ( parent_id, child_id, bt_position, refer_id, bt_level) VALUES ( 14, 17, 'L', null, 3);
INSERT INTO public.binary_tree ( parent_id, child_id, bt_position, refer_id, bt_level) VALUES ( 15, 10, 'L', null, 3);
INSERT INTO public.binary_tree ( parent_id, child_id, bt_position, refer_id, bt_level) VALUES ( 15, 11, 'R', null, 3);
INSERT INTO public.binary_tree ( parent_id, child_id, bt_position, refer_id, bt_level) VALUES ( 3, 19, 'L', null, 2);



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
BEGIN
  --check if root exists
  /*  SELECT COUNT(*)
             FROM binary_tree
             WHERE parent_id IS NULL AND bt_position = 'N');*/

  SELECT EXISTS(SELECT 1
                FROM binary_tree
                WHERE parent_id=0 AND bt_position = 'N') INTO is_root_exists;


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
    FROM binary_tree
    WHERE parent_id = 0 AND binary_tree.bt_position='N' --todo depends on INDEXES, UNIQUES, PK

    UNION ALL

    SELECT
      bt.child_id,
      cn.ancestors || bt.parent_id,
      cn.positions || bt.bt_position
    FROM binary_tree bt, child_nodes cn
    WHERE bt.parent_id = cn.child_id
  ) SELECT unnest(ancestors) AS ancestor,unnest(positions) AS position
    FROM child_nodes
    WHERE child_id = item_id;

END;
$$ LANGUAGE 'plpgsql';


CREATE OR REPLACE FUNCTION bt_get_descendants(parentId INTEGER, maxLevel INTEGER DEFAULT 3)
  RETURNS TABLE(id int,parent_id int , child_id int,  bt_position e_binary_position, bt_level int, level int) AS $$
DECLARE
  parent_level INTEGER;
BEGIN

  SELECT
        bt.bt_level INTO parent_level
          FROM binary_tree bt
            WHERE bt.parent_id = parentId;

  RAISE NOTICE 'Parent bt_level level is %', parent_level;


  maxLevel:=maxLevel + parent_level;

  RAISE NOTICE 'Relative max bt_level level is %', maxLevel;


  RETURN QUERY
  WITH RECURSIVE child_nodes AS (
    (
      SELECT
        bt.*, 1 as auto_level
      FROM binary_tree bt
      WHERE bt.parent_id = parentId
    )
    UNION
    SELECT
      bt.*, cn.auto_level+1 as auto_level
    FROM child_nodes cn, binary_tree bt
    WHERE bt.parent_id = cn.child_id

  ) SELECT
      cn.id, cn.parent_id, cn.child_id, cn.bt_position, cn.bt_level, auto_level
    FROM child_nodes cn WHERE auto_level <= maxLevel;
END;
$$ LANGUAGE 'plpgsql';



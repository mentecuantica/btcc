--SELECT addchild(14,'R','38')
SELECT * FROM bt_add_to_parent(5666,'L',1500) as result;

SELECT * FROM bt_get_descendants(7);

SELECT COUNT(child_id)
    FROM binary_tree
    WHERE parent_id = 7;


SELECT json_object_agg(child_id,json_build_array(bt_position,bt_level,parent_id))
    FROM binary_tree
    WHERE parent_id = 7;


INSERT INTO binary_tree (parent_id, child_id, bt_position, bt_level) VALUES (NULL ,1,'N',0);

SELECT array_length(a::int[],0) FROM (SELECT array_agg(child_id)
    FROM binary_tree
    WHERE parent_id = 7 ) as a;



WITH RECURSIVE child_nodes AS (
    (
      SELECT
        bt.*, 1 as auto_level
      FROM binary_tree bt
      WHERE parent_id = 7
    )
    UNION
    SELECT
      bt.*, cn.auto_level+1 as auto_level
    FROM child_nodes cn, binary_tree bt
    WHERE bt.parent_id = cn.child_id

  ) SELECT
      *
    FROM child_nodes



SELECT generate_subscripts('{NULL,1,NULL,2}'::int[], 1) AS s;

SELECT * FROM bt_find_ancestors(5666)
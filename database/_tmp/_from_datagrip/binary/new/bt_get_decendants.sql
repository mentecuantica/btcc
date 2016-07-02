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
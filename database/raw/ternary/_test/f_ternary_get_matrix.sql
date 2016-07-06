CREATE OR REPLACE FUNCTION f_ternary_get_matrix(parentId INTEGER, maxLevel INTEGER DEFAULT 3)
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
      WHERE bt.child_id = parentId
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
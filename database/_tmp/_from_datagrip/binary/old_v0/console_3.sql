WITH RECURSIVE child_nodes AS (
    (
      SELECT
        bt.*, 1 as auto_level, ARRAY[bt.parent_id] as path
      FROM binary_tree bt
      WHERE parent_id = 7
    )
    UNION
    SELECT
      bt.*, cn.auto_level+1 as auto_level, cn.path || bt.parent_id as path
    FROM child_nodes cn, binary_tree bt
    WHERE bt.parent_id = cn.child_id

  ) SELECT
      *
    FROM child_nodes ORDER BY parent_id DESC





WITH RECURSIVE helper_tree AS (
  SELECT child_id, ARRAY[]::INTEGER[] as ancestors
  FROM binary_tree bt WHERE bt.parent_id = 7

  UNION ALL
      SELECT bt.child_id, ht.ancestors || bt.parent_id
  FROM binary_tree bt, helper_tree ht WHERE bt.parent_id = ht.child_id


) SELECT child_id FROM helper_tree;




WITH RECURSIVE child_nodes AS (
  SELECT id, child_id, ARRAY[1]::INTEGER[] as ancestors
  FROM binary_tree bt WHERE bt.parent_id = 7

  UNION ALL
      SELECT bt.id, bt.child_id, ht.ancestors || bt.parent_id
  FROM binary_tree bt, child_nodes ht WHERE bt.parent_id = ht.child_id


) SELECT child_id FROM child_nodes
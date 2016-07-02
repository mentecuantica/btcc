WITH RECURSIVE child_nodes AS (
  --SELECT id, child_id, ARRAY[]::INTEGER[] as ancestors
  SELECT id, child_id, parent_id, ARRAY[parent_id] as ancestors, 0 as level
  FROM binary_tree bt WHERE bt.parent_id = 7

  UNION ALL
      SELECT bt.id, bt.child_id,bt.parent_id, cn.ancestors || bt.parent_id, cn.level+1 as level
  FROM binary_tree bt, child_nodes cn WHERE bt.parent_id = cn.child_id


) SELECT parent_id,child_id, level FROM child_nodes












WITH RECURSIVE child_nodes AS (
  --SELECT id, child_id, ARRAY[]::INTEGER[] as ancestors
  SELECT id, child_id, parent_id, ARRAY[parent_id] as ancestors, 0 as level
  FROM binary_tree bt WHERE bt.parent_id = 7

  UNION ALL
      SELECT bt.id, bt.child_id,bt.parent_id, cn.ancestors || bt.parent_id, cn.level+1 as level
  FROM binary_tree bt, child_nodes cn WHERE bt.parent_id = cn.child_id


) SELECT parent_id,level,array_agg(child_id) as children_ids FROM child_nodes GROUP BY level,parent_id ORDER BY level;
--) SELECT parent_id,array_agg(child_id) FROM child_nodes GROUP BY parent_id,


SELECT bt_add_to_parent(1000,'L',9999);
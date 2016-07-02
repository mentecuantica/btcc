WITH RECURSIVE child_nodes AS (
  (
    SELECT
      tub.*, 1 as level
    FROM binary_tree tub
    WHERE parent_id = 1
  )

  UNION

  SELECT
      ub.*, cn.level + 1 as level
  FROM child_nodes cn, binary_tree ub
  WHERE ub.parent_id = cn.child_id

)
SELECT
  cn.*, tu.name
FROM child_nodes cn, t_users tu
WHERE cn.child_id = tu.id
--GROUP BY parent_id, name
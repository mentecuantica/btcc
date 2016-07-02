
WITH RECURSIVE child_nodes AS (

  (
			SELECT parent_id, child_id, bt_position, bt_level FROM binary_tree WHERE parent_id = 5666

	)

	UNION

	SELECT ub.parent_id, ub.child_id, ub.bt_position, ub.bt_level
	FROM child_nodes, binary_tree ub
	WHERE ub.parent_id = child_nodes.child_id

)
SELECT parent_id, child_id, bt_position, bt_level FROM child_nodes --, t_users WHERE child_nodes.child_id=t_users.id AND placement='R' AND depth=2

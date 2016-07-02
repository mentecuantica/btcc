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
    WHERE parent_id IS NULL

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






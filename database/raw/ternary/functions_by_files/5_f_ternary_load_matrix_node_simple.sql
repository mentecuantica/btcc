

DROP FUNCTION IF EXISTS public.ternary_load_matrix_node_simple(pid INTEGER );
CREATE OR REPLACE FUNCTION public.ternary_load_matrix_node_simple(pid INTEGER)
   RETURNS SETOF T_MATRIX_NODE_SIMPLE
AS
$$
DECLARE

  r T_MATRIX_NODE_SIMPLE;
BEGIN

  RETURN QUERY SELECT
    t1.user_id                                      AS user_id,
    t1.level_abs,
    t1.parent_id,
    string_to_array(t1.path_to_root, ',')::INTEGER[]            AS root_path_array,
    ARRAY [t1.l, t1.m, t1.r]                         AS children_positions,
    ARRAY [t1.l, t1.m, t1.r] @> ARRAY [pid]          AS has_free_place,
    ARRAY [t1.l, t1.m, t1.r] = ARRAY [pid, pid, pid] AS is_leaf,
    ARRAY [t1.l = pid, t1.m = pid, t1.r = pid]       AS free_places
  --INTO r
  FROM tree_ternary t1
  WHERE t1.user_id = pid;
  --RETURN r;

END;
$$
LANGUAGE 'plpgsql';
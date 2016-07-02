CREATE OR REPLACE FUNCTION public.load_matrix(pid INT)
  RETURNS TABLE( user_id INT, parent_level INT,
                 childs JSONB,  positions JSONB,
                 positions_array e_ternary_position[], child_id INTEGER, is_leaf BOOL,  child_level INTEGER)
AS
$$
BEGIN
  RETURN QUERY SELECT
                 t1.child_id                               AS user_id,
                 t1.level_abs                               AS parent_level,
                 json_agg(t2.child_id)
                 OVER (PARTITION BY t1.parent_id) :: JSONB AS childs,
                 json_agg(t2.t_position)
                 OVER (PARTITION BY t1.parent_id) :: JSONB AS positions,
                 array_agg(t2.t_position)
                 OVER (PARTITION BY t1.parent_id)          AS positions_array,
                 t2.child_id                               AS child_id,
                 t2.t_position,
                 CASE WHEN t2.child_id ISNULL
                   THEN TRUE
                 ELSE FALSE END                            AS is_leaf,
                 CASE WHEN t2.t_position = 'L'
                   THEN t2.child_id
                 ELSE NULL END                             AS "l",

                 t2.level_abs                               AS child_level
               FROM tree_ternary t1 LEFT OUTER JOIN tree_ternary t2 ON (t1.child_id = t2.parent_id)
               WHERE t1.child_id = pid;
END
$$
LANGUAGE 'plpgsql' STABLE;
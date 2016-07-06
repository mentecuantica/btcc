

DROP FUNCTION IF EXISTS public.load_matrix_position(pid INTEGER);
CREATE OR REPLACE FUNCTION public.load_matrix_position(pid INTEGER)
  RETURNS t_matrix_position
AS
$$
BEGIN
  RETURN QUERY SELECT
                 t1.user_id                               AS user_id,
                 t1.level_abs                               AS parent_level,
                 array_agg(t2.t_position)
                 OVER (PARTITION BY t1.parent_id)          AS positions_array,
                 t2.user_id                               AS user_id,
                 t2.t_position,
                 CASE WHEN t2.user_id ISNULL
                   THEN TRUE
                 ELSE FALSE END                            AS is_leaf,
                 /*CASE WHEN t2.t_position = 'L'
                   THEN t2.user_id
                 ELSE NULL END                             AS "l",*/

                 t2.level_abs                               AS child_level
               FROM tree_ternary t1 LEFT OUTER JOIN tree_ternary t2 ON (t1.user_id = t2.parent_id)
               WHERE t1.user_id = pid;
END
$$
LANGUAGE 'plpgsql' STABLE;
DROP FUNCTION IF EXISTS public.ternary_get_nodes_with_places_by_level(absoluteLevel INTEGER );

CREATE OR REPLACE FUNCTION public.ternary_get_nodes_with_places_by_level(absoluteLevel INTEGER)
  RETURNS TABLE(user_id INTEGER,
  parent_id INTEGER,
  created_at TIMESTAMP,
    level_abs INTEGER,
    position_on_level INTEGER,
    root_path_array INTEGER[],
  children_positions INTEGER[],
  is_leaf         BOOL,
  free_places BOOLEAN[])
AS
$BODY$

  SELECT t1.user_id                                      ,
    t1.parent_id,
    t1.created_at,
    t1.level_abs,
    t1.position_on_level,
    string_to_array(t1.path_to_root, ',')::INTEGER[]            AS root_path_array,
    ARRAY [t1.l, t1.m, t1.r]                         AS children_positions,
    ARRAY [t1.l, t1.m, t1.r] = ARRAY [user_id, user_id, user_id] AS is_leaf,
    ARRAY [t1.l = user_id, t1.m = user_id, t1.r = user_id]       AS free_places
  FROM tree_ternary t1 WHERE level_abs=absoluteLevel AND (ARRAY[l,m,r] @> ARRAY[user_id]);
$BODY$ LANGUAGE SQL;



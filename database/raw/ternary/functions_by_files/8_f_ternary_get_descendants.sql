DROP FUNCTION IF EXISTS ternary_get_descendants(parentId INTEGER, maxLevel INTEGER);

CREATE OR REPLACE FUNCTION ternary_get_descendants(parentId INTEGER, maxLevel INTEGER DEFAULT 3)
  RETURNS TABLE(parent_id int , user_id int,  t_position e_ternary_position, depth int, level_rel int,path_to_root VARCHAR) AS $$
DECLARE
  parent_level INTEGER;
BEGIN

  SELECT
    tt.level_abs INTO parent_level
  FROM tree_ternary tt
  WHERE tt.parent_id = parentId;

  RAISE NOTICE 'Parent depth level is %', parent_level;


  maxLevel:=maxLevel + parent_level;

  RAISE NOTICE 'Relative max depth level is %', maxLevel;


RETURN QUERY
WITH RECURSIVE child_nodes AS (
    (
      SELECT
        tt.*, 1 as auto_level
      FROM tree_ternary tt
      WHERE tt.user_id = parentId
    )
    UNION
    SELECT
      tt.*, cn.auto_level+1 as auto_level
    FROM child_nodes cn, tree_ternary tt
    WHERE tt.parent_id = cn.user_id

  ) SELECT
       cn.parent_id, cn.user_id, cn.t_position, cn.level_abs as depth, auto_level as level_rel,cn.path_to_root
    FROM child_nodes cn WHERE auto_level <= maxLevel;
END;
$$ LANGUAGE 'plpgsql';


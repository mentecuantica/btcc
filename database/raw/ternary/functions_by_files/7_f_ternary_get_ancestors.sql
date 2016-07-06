
DROP FUNCTION IF EXISTS public.ternary_get_ancestors(nodeId INTEGER );
CREATE OR REPLACE FUNCTION public.ternary_get_ancestors(nodeId INTEGER)
  RETURNS TABLE(id INTEGER)
  -- returns TABLE of ancestors ID
AS
$BODY$
  DECLARE
  var_temp  BOOL :=FALSE;
BEGIN
  RETURN QUERY

  SELECT * FROM  (SELECT regexp_split_to_table(tt.path_to_root,',')::INTEGER as id
               FROM tree_ternary tt WHERE user_id = nodeId) as all_ancestors

    WHERE all_ancestors.id <> nodeId;

END

$BODY$ LANGUAGE 'plpgsql';


DROP FUNCTION IF EXISTS public.ternary_get_ancestors_array(nodeId INTEGER );
CREATE OR REPLACE FUNCTION public.ternary_get_ancestors_array(nodeId INTEGER)
  RETURNS INTEGER[]
AS
$BODY$
  DECLARE
  var_temp  BOOL :=FALSE;
BEGIN
  --SELECT unnest(t) as id FROM ternary_get_ancestors_array(7) as t

    RETURN array_remove(string_to_array(tt.path_to_root,',')::INTEGER[],nodeId)
  FROM tree_ternary tt WHERE user_id = nodeId;


END

$BODY$ LANGUAGE 'plpgsql';


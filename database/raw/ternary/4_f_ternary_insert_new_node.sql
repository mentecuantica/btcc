DROP FUNCTION IF EXISTS public.ternary_insert_new_node(pNode t_matrix_node_simple, nNode tree_ternary );

CREATE OR REPLACE FUNCTION public.ternary_insert_new_node(pNode t_matrix_node_simple, nNode tree_ternary)
  RETURNS INTEGER
AS
$BODY$
DECLARE
  new_node          tree_ternary%ROWTYPE;
  existing_children INTEGER ARRAY;
  new_position      E_TERNARY_POSITION;
  new_node_id       tree_ternary.user_id%TYPE;
  added_node_id     tree_ternary.user_id%TYPE;
  parent_node_id    INTEGER;
  update_stmt TEXT;
BEGIN

  new_node_id:=nNode.user_id;
  parent_node_id:=nNode.parent_id;
  RAISE NOTICE 'Insert newNode: % ,position:% , level: %,as child to parentNode %',
  new_node_id, nNode.t_position::CHARACTER, nNode.level_abs, parent_node_id;

  nNode.path_to_root:=array_to_string(array_prepend(new_node_id, pNode.root_path_array), ',');
  nNode.level_abs = pNode.level_abs + 1;
  nNode.l = new_node_id;
  nNode.m = new_node_id;
  nNode.r = new_node_id;

  RAISE NOTICE 'Set newNode path_to_root % , level_abs:% , l,m,r - to default %',
  nNode.path_to_root, nNode.level_abs, new_node_id;

  --places on levels query
  /*  SELECT level_abs,max(position_on_level) as last_on_level, pow(3,level_abs) as level_limit, pow(3,level_abs) - max(position_on_level) as places_on_level
      FROM tree_ternary
    GROUP BY level_abs ORDER BY level_abs; */

  IF EXISTS (SELECT 1 FROM tree_ternary WHERE level_abs = nNode.level_abs GROUP BY level_abs) THEN
      WITH pos_on_level AS (
          SELECT
            max(position_on_level)                     AS last_on_level,
            pow(3, level_abs)                          AS level_limit,
            pow(3, level_abs) - max(position_on_level) AS places_on_level
          FROM tree_ternary
          WHERE level_abs = nNode.level_abs
          GROUP BY level_abs
      )
      SELECT CASE
             WHEN last_on_level ISNULL THEN 1
             ELSE last_on_level+1 END
      FROM pos_on_level
      INTO nNode.position_on_level;
  ELSE
    nNode.position_on_level:=1;
  END IF;

  RAISE NOTICE 'Set newNode.position_on_level %', nNode.position_on_level;


  WITH inserted AS (
    INSERT INTO tree_ternary (parent_id, user_id, t_position, level_abs, l, m, r, position_on_level, path_to_root)
    --INSERT INTO tree_ternary
    VALUES (nNode.parent_id, nNode.user_id,nNode.t_position,nNode.level_abs,nNode.l,
            nNode.m,nNode.r,nNode.position_on_level,nNode.path_to_root)
    --SELECT (nNode).*
    RETURNING *
  )

  SELECT inserted.user_id
  FROM inserted
  INTO added_node_id;


    SELECT format('UPDATE tree_ternary SET %I = $1 WHERE user_id=$2',t1.t_pos) INTO update_stmt FROM
    (SELECT
       unnest(ARRAY['l','m','r']) as t_pos,
       unnest(ARRAY[l,m,r]) AS t_cid
     FROM tree_ternary t
 WHERE t.user_id = parent_node_id ) as t1 WHERE t_cid=parent_node_id LIMIT 1;

   RAISE WARNING 'Update stmt UPDATE tree_ternary SET % = % WHERE user_id=%','LETTER',new_node_id,parent_node_id;

  EXECUTE update_stmt USING new_node_id,parent_node_id;

  RAISE WARNING 'If row updated %',FOUND;



  RETURN added_node_id;


END;

$BODY$ LANGUAGE 'plpgsql';




  --SELECT (nNode).* INTO TEMP TABLE matrix_node_simple_;

  --INSERT INTO matrix_node_simple_ SELECT (nNode).*;

 --UPDATE
 -- SELECT ARRAY[NULLIF(t.l,3),NULLIF(t.m,3),NULLIF(t.r,3)] FROM tree_ternary t WHERE user_id=3;
  --SELECT * FROM tree_ternary WHERE parent_id=3 ORDER BY t_position;



/*
    SELECT format('UPDATE tree_ternary SET %I = %L WHERE user_id=%L',t1.p,4,4) INTO update_stmt FROM
    (SELECT unnest(ARRAY['l','m','r']) as P,unnest(ARRAY[l,m,r]) AS val  FROM tree_ternary t1
 WHERE t1.user_id =4 ) as t1 WHERE val=4 LIMIT 1;
*/

/*
  UPDATE tree_ternary SET ;
SELECT string_agg(t1.t_position::text || '=' || t1.user_id::text,',') FROM tree_ternary t1
 WHERE t1.parent_id =3 ;
  SELECT t_position::text || user_id::text FROM tree_ternary WHERE parent_id =3;
*/

  --SELECT t.l=3 as l,t.m=3 as m,COALESCE(NULLIF(t.r,'3'),'r') as r FROM tree_ternary t WHERE user_id=3;
  --SELECT t.l=3 as l,t.m=3 as m,COALESCE(NULLIF(t.r,'3'),'r') as r FROM tree_ternary t WHERE user_id=3;
  --SELECT COALESCE(NULLIF(t.l,'3'),'0') as l,COALESCE(NULLIF(t.m,'3'),'0') as m,COALESCE(NULLIF(t.r,'3'),'0') as r FROM tree_ternary t WHERE user_id=3
  --SELECT COALESCE(t.l,t.m,t.r) FROM (SELECT t.l=3 as l,t.m=3 as m,t.r=3 as r FROM tree_ternary t WHERE user_id=3) as t;
  --SELECT COALESCE(t.l=3,t.m=3,t.r=3),NULLIF(t.l,3) FROM tree_ternary t WHERE user_id=3;
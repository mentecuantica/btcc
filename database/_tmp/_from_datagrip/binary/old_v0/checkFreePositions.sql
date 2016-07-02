CREATE OR REPLACE FUNCTION checkFreePositions(parentId INTEGER)
  RETURNS CHARACTER AS $$
DECLARE
  l_depth integer:=NULL ;
  m_row t_users_binary%ROWTYPE;
  b_right_free BOOL;
  b_left_free BOOL;

  len INTEGER;
  appendToId int;
  --direct_free ARRAY::CHAR;
  direct_free VARCHAR(10);
  childrens binary_node_nameless1;
BEGIN
    appendToId:=parentId;
  -- ALL CHILDRENS
   --SELECT array_agg(placement) INTO direct_free FROM getdirectchildren(parentId);
   SELECT string_agg(gtc.placement::VARCHAR(1),',') INTO direct_free FROM getdirectchildren(parentId) as gtc;


  if strpos(direct_free,'L')=0 THEN
       RETURN 'L';
  END IF;

  if strpos(direct_free,'R')=0 THEN
       RETURN 'R';
  END IF;
  
  RETURN 'N';
  
  /* if strpos(direct_free,'R')=0 THEN
       RETURN ROW(appendToId,'R');
  END IF;*/

  -- len:=array_length(direct_free,1);

  /* if len=0 THEN
     RAISE NOTICE 'No direct places';
   END IF;*/
  --CHECK FOR L OR R CHILD


    --1.1 Choose parent, position (L,R)
   /*IF (depthLevel IS NULL) THEN
     RETURN QUERY SELECT tub.parent_id, tub.placement FROM t_users_binary as tub;
   END IF;*/

  --RETURN QUERY SELECT tub.parent_id, tub.placement FROM t_users_binary as tub;
END;
$$ LANGUAGE 'plpgsql';
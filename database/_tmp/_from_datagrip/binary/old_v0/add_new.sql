--- OBSOLETE @see bt_add_to_parent()

CREATE OR REPLACE FUNCTION addChild1(parentId INTEGER, position_to_append binary_position, userId INTEGER)
  RETURNS BOOL AS $$
DECLARE
  l_depth integer:=NULL ;
  m_row t_users_binary%ROWTYPE;
  b_right_free BOOL;
  b_left_free BOOL;

  len INTEGER;
  appendToId int;
  --direct_free ARRAY::CHAR;
  direct_free VARCHAR(10);
  free_position CHAR;
  new_depth INTEGER;
  childrens binary_node_nameless1;
BEGIN
    appendToId:=parentId;

   SELECT depth FROM getdirectchildren(parentId) INTO new_depth;

   RAISE NOTICE 'Parent depth level is %', new_depth;

   --new_depth:=new_depth+1;
   SELECT checkfreepositions(parentId) INTO free_position;

   if (free_position='N') THEN
      RAISE NOTICE 'No free positions at parent node %', parentId;
      RETURN FALSE;
   END IF;

   if (free_position=position_to_append::CHAR) THEN
      RAISE NOTICE 'Position to append is open: %', position_to_append;
      INSERT INTO t_users_binary (id, parent_id, child_id, placement, refer_id, depth) VALUES (CEIL(RANDOM()*1000), appendToId, userId, position_to_append::CHAR, 0, new_depth);
      RETURN TRUE ;
   END IF;



  RAISE NOTICE 'Need to find another...';
  RETURN FALSE;



END;
$$ LANGUAGE 'plpgsql';
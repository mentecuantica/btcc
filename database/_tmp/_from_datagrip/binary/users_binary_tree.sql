-- USERS_BINARY_TREE TABLE

CREATE TYPE e_binary_position AS ENUM (
  'L',
  'R',
  'N');


CREATE TABLE public.users_binary_tree (
  id SERIAL PRIMARY KEY NOT NULL ,
  parent_id INTEGER NOT NULL,
  child_id INTEGER NOT NULL,
  placement e_binary_position NOT NULL DEFAULT 'N',
  refer_id INTEGER,
  depth INTEGER
);



ALTER TABLE public.users_binary_tree
ADD CONSTRAINT "unq_child_id" UNIQUE ("child_id"),
ADD CONSTRAINT "unq_child_parent" UNIQUE ("parent_id", "child_id");

--ADD CONSTRAINT "fk" FOREIGN KEY ("parent_id") REFERENCES "public"."users" ("id") ON DELETE CASCADE ON UPDATE CASCADE;
CREATE INDEX "idx_parent_id" ON "public"."users_binary_tree" ("parent_id");
CREATE INDEX "idx_parent_child" ON "public"."users_binary_tree" ("parent_id","child_id");



-- ROOT
INSERT INTO public.users_binary_tree ( parent_id, child_id, placement, refer_id, depth) VALUES ( 0, 7, 'N', null, 0);

--- OTHERS
INSERT INTO public.users_binary_tree ( parent_id, child_id, placement, refer_id, depth) VALUES ( 7, 22, 'L', null, 1);
INSERT INTO public.users_binary_tree ( parent_id, child_id, placement, refer_id, depth) VALUES ( 7, 33, 'R', null, 1);
INSERT INTO public.users_binary_tree ( parent_id, child_id, placement, refer_id, depth) VALUES ( 22, 14, 'L', null, 2);
INSERT INTO public.users_binary_tree ( parent_id, child_id, placement, refer_id, depth) VALUES ( 22, 15, 'R', null, 2);
INSERT INTO public.users_binary_tree ( parent_id, child_id, placement, refer_id, depth) VALUES ( 33, 16, 'R', null, 2);
INSERT INTO public.users_binary_tree ( parent_id, child_id, placement, refer_id, depth) VALUES ( 14, 17, 'L', null, 3);
INSERT INTO public.users_binary_tree ( parent_id, child_id, placement, refer_id, depth) VALUES ( 15, 10, 'L', null, 3);
INSERT INTO public.users_binary_tree ( parent_id, child_id, placement, refer_id, depth) VALUES ( 33, 19, 'L', 0, 3);
INSERT INTO public.users_binary_tree ( parent_id, child_id, placement, refer_id, depth) VALUES ( 15, 11, 'R', 0, 4);



CREATE OR REPLACE FUNCTION addChild(parentId INTEGER, position_to_append e_binary_position, userId INTEGER)
  RETURNS BOOL AS $$
DECLARE
  l_depth integer:=NULL ;
  m_row users_binary_tree%ROWTYPE;
  b_right_free BOOL;
  b_left_free BOOL;

  len INTEGER;
  appendToId int;
  --direct_free ARRAY::CHAR;
  direct_free VARCHAR(10);
  free_position CHAR;
  new_depth INTEGER;
  --childrens binary_node_nameless1;
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
      INSERT INTO users_binary_tree (parent_id, child_id, placement, refer_id, depth) VALUES (appendToId, userId, position_to_append::CHAR, 0, new_depth);
      RETURN TRUE ;
   END IF;



  RAISE NOTICE 'Need to find another...';
  RETURN FALSE;



END;
$$ LANGUAGE 'plpgsql';


CREATE OR REPLACE FUNCTION getDescendants(parentId INTEGER, maxDepth INTEGER DEFAULT 3)
  RETURNS SETOF users_binary_tree AS $$
DECLARE
  result users_binary_tree%ROWTYPE;
  parent_depth_level INTEGER;
BEGIN

  SELECT
        depth INTO parent_depth_level
          FROM users_binary_tree
            WHERE parent_id = parentId;

  RAISE NOTICE 'Parent depth level is %', parent_depth_level;


  maxDepth:=maxDepth + parent_depth_level;

  RAISE NOTICE 'Relative max depth level is %', maxDepth;


  RETURN QUERY
  WITH RECURSIVE child_nodes AS (
    (
      SELECT
        *
      FROM users_binary_tree
      WHERE parent_id = parentId
    )
    UNION
    SELECT
      ub.*
    FROM child_nodes, users_binary_tree ub
    WHERE ub.parent_id = cn.child_id

  ) SELECT
      *
    FROM child_nodes WHERE depth <= maxDepth;
END;
$$ LANGUAGE 'plpgsql';


CREATE OR REPLACE FUNCTION checkFreePositions(parentId INTEGER)
  RETURNS CHARACTER AS $$
DECLARE
  l_depth integer:=NULL ;
  m_row users_binary_tree%ROWTYPE;
  b_right_free BOOL;
  b_left_free BOOL;

  len INTEGER;
  appendToId int;
  --direct_free ARRAY::CHAR;
  direct_free VARCHAR(10);
  --childrens binary_node_nameless1;
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

END;
$$ LANGUAGE 'plpgsql';

--stupid function
CREATE OR REPLACE FUNCTION public.getdirectchildren(parentid int4)
  RETURNS SETOF users_binary_tree
AS
$BODY$
  DECLARE
  depth integer:=NULL ;
  m_row users_binary_tree%ROWTYPE;
BEGIN
   RETURN QUERY SELECT * FROM getdescendants(parentid) WHERE parent_id = parentid;
END;
$BODY$
LANGUAGE plpgsql VOLATILE;


---

  CREATE TABLE binary_tree AS (
	id serial, -- ??
  user_id int, -- ?? ???? ?? ? ???? parent_id
	parent_id int NULL,
	child_id int NULL,
	bt_position ENUM ('L','R') NULL,
	l_child_id int NULL,
	r_child_id int NULL,
	refer_id int NULL,
	bt_level int
  )

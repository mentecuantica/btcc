-- таблица на всякие случаи хранения бинарного
CREATE TYPE e_binary_position AS ENUM (
  'L',
  'R',
  'N');

CREATE TABLE public.tree_binary (
  id SERIAL NOT NULL ,
  --user_id int, -- по сути он и есть parent_id
	parent_id int NULL,
	child_id int NOT NULL,
	bt_position e_binary_position NOT NULL,
	l_child_id int NULL ,
	r_child_id int NULL ,
	refer_id int NULL ,
	depth int NULL ,
  info TEXT,
	PRIMARY KEY (parent_id, child_id),
	UNIQUE(parent_id, child_id),
	UNIQUE(parent_id, child_id, bt_position)

	--   parent_id INTEGER REFERENCES tree_binary(parent_id) ???

);

CREATE INDEX "idx_parent_id" ON "public"."tree_binary" ("parent_id");
CREATE INDEX "idx_parent_child" ON "public"."tree_binary" ("parent_id","child_id");

-- ROOT
INSERT INTO public.tree_binary ( parent_id, child_id, bt_position, refer_id, depth) VALUES ( 0 , 1, 'N', null, 0);

--- OTHERS
INSERT INTO public.tree_binary ( parent_id, child_id, bt_position, refer_id, depth) VALUES ( 1, 2, 'L', null, 1);
INSERT INTO public.tree_binary ( parent_id, child_id, bt_position, refer_id, depth) VALUES ( 1, 3, 'R', null, 1);
INSERT INTO public.tree_binary ( parent_id, child_id, bt_position, refer_id, depth) VALUES ( 2, 4, 'L', null, 2);
INSERT INTO public.tree_binary ( parent_id, child_id, bt_position, refer_id, depth) VALUES ( 2, 5, 'R', null, 2);
INSERT INTO public.tree_binary ( parent_id, child_id, bt_position, refer_id, depth) VALUES ( 3, 6, 'R', null, 2);
INSERT INTO public.tree_binary ( parent_id, child_id, bt_position, refer_id, depth) VALUES ( 4, 7, 'L', null, 3);
INSERT INTO public.tree_binary ( parent_id, child_id, bt_position, refer_id, depth) VALUES ( 5, 8, 'L', null, 3);
INSERT INTO public.tree_binary ( parent_id, child_id, bt_position, refer_id, depth) VALUES ( 5, 9, 'R', null, 3);
INSERT INTO public.tree_binary ( parent_id, child_id, bt_position, refer_id, depth) VALUES ( 3, 10, 'L', null, 2);




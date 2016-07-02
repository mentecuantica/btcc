DROP TYPE IF EXISTS E_TERNARY_POSITION CASCADE;
CREATE TYPE E_TERNARY_POSITION AS ENUM ('L', 'M', 'R');

DROP TYPE IF EXISTS t_matrix_node_simple CASCADE ;

CREATE TYPE  T_MATRIX_NODE_SIMPLE  AS (user_id INTEGER, level_abs INTEGER, parent_id INTEGER,
                root_path_array INTEGER [], children_positions INTEGER [], has_free_place BOOL,
                is_leaf         BOOL, free_places BOOLEAN []);

DROP TYPE IF EXISTS T_MATRIX_POSITION CASCADE;
CREATE TYPE T_MATRIX_POSITION  AS (user_id INTEGER, parent_level INTEGER,
                 positions_array e_ternary_position[],
               is_leaf BOOL,  child_level INTEGER);


DROP TABLE IF EXISTS tree_ternary CASCADE ;

--Tree_ternary table, @todo add time ?
CREATE TABLE public.tree_ternary (
  --id SERIAL, -- @todo Do we need serial ID ??
  user_id INTEGER NOT NULL, -- user_id actually
  parent_id INTEGER NOT NULL,
  created_at TIMESTAMP DEFAULT NOW() NOT NULL ,
  t_position E_TERNARY_POSITION NOT NULL , -- Position in ternary TREE
  level_abs INTEGER, -- Absolute depth level from root
  l INTEGER NOT NULL, -- Children position in L, default = user_id
  m INTEGER NOT NULL, -- Children position in M, default = user_id
  r INTEGER NOT NULL, -- Children position in R, default = user_id
  position_on_level INTEGER, -- Position on level, we can count it, then decide if level is full or not
  path_to_root CHARACTER VARYING NOT NULL DEFAULT '1'::character varying, -- Path to root (materialized path),
  PRIMARY KEY (parent_id, user_id)
);
CREATE UNIQUE INDEX ter_exclusive ON tree_ternary USING BTREE (parent_id, user_id, t_position);
CREATE UNIQUE INDEX idx_parent_child_copy ON tree_ternary USING BTREE (parent_id, user_id);
CREATE UNIQUE INDEX user_id ON tree_ternary USING BTREE (user_id);
ALTER TABLE tree_ternary ADD CONSTRAINT ternary_max_on_level CHECK( (3^level_abs-position_on_level)>=0);
COMMENT ON COLUMN public.tree_ternary.t_position IS 'Position in ternary TREE';
COMMENT ON COLUMN public.tree_ternary.level_abs IS 'Absolute depth level from root';
COMMENT ON COLUMN public.tree_ternary.l IS 'Children position in L, default = user_id';
COMMENT ON COLUMN public.tree_ternary.m IS 'Children position in M, default = user_id';
COMMENT ON COLUMN public.tree_ternary.r IS 'Children position in R, default = user_id';
COMMENT ON COLUMN public.tree_ternary.position_on_level IS 'Position on level, we can count it, then decide if level is full or not';
COMMENT ON COLUMN public.tree_ternary.path_to_root IS 'Path to root (materialized path)';


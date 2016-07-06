--Trigger function
DROP FUNCTION IF EXISTS ternary_trigger_handler_add_matrix_relation();
CREATE OR REPLACE FUNCTION public.ternary_trigger_handler_add_matrix_relation()
  RETURNS trigger AS
$__$
BEGIN
  IF TG_OP = 'INSERT' THEN
    RAISE WARNING 'We have inserted the linear user %',NEW.user_id;
  ELSEIF TG_OP = 'UPDATE' THEN
    --CHECK THAT user_id has no parent id, and depth = 0 and left-rgt = 1
    --and parent_id supplied
    -- after call tt_add_to_matrix(user_id, parent_id)
    RAISE WARNING 'We have updated the linear user %',NEW.user_id;
  END IF;

  RETURN new;
END ;
$__$ language plpgsql;

--Trigger on update or insert tree linear
--DROP TRIGGER IF EXISTS ternary_insert_tree_linear_trg;

CREATE TRIGGER t_ternary_insert_tree_linear_trg
AFTER INSERT OR UPDATE ON tree_linear
FOR EACH ROW
EXECUTE PROCEDURE ternary_trigger_handler_add_matrix_relation();
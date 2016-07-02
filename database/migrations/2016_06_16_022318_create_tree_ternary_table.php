<?php

use Database\Schema\ExtendedBlueprint;
use Database\Schema\ExtendedPostrgresGrammer;
use Illuminate\Database\Migrations\Migration;

class CreateTreeTernaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::beginTransaction();

        DB::connection()->setSchemaGrammar(new ExtendedPostrgresGrammer());
        $schema = DB::connection()->getSchemaBuilder();

        // replace blueprint
        $schema->blueprintResolver(function($table, $callback) {
            return new ExtendedBlueprint($table, $callback);
        });



        $this->createTable();
        $this->seedInitialTree();
        $this->createPgSqlFunctions();

        DB::commit();
    }

    protected function createTable()
    {
        $rawSQL = file_get_contents(__DIR__.'/../raw/ternary/1_t_create_ternary_table.sql');
        DB::connection()->getPdo()->exec($rawSQL);



    }
    protected function createPgSqlFunctions()
    {

        //$rawSQL = file_get_contents(__DIR__.'/../raw/ternary/1_t_create_ternary_table.sql');
        //DB::connection()->getPdo()->exec($rawSQL);
    }
    
    protected function seedInitialTree()
    {
        $rawSQL = file_get_contents(__DIR__.'/../raw/ternary/2_insert_inital_data.sql');
        DB::connection()->getPdo()->exec($rawSQL);
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $createType = "DROP TYPE IF EXISTS e_binary_position CASCADE"; // this will delete all the functions
        $dropFunction1 = "DROP FUNCTION IF EXISTS bt_get_descendants";
        $dropFunction2 = "DROP FUNCTION IF EXISTS bt_get_ancestors";
        $dropFunction3 = "DROP FUNCTION IF EXISTS bt_add_to_parent";

        DB::connection()->getPdo()->exec($createType);
        Schema::drop('tree_binary');
    }
}

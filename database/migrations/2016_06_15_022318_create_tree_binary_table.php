<?php

use Database\Schema\ExtendedBlueprint;
use Database\Schema\ExtendedPostrgresGrammer;
use Illuminate\Database\Migrations\Migration;

class CreateTreeBinaryTable extends Migration
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


        $createType = "DROP TYPE IF EXISTS e_binary_position CASCADE";
        DB::connection()->getPdo()->exec($createType);
        $createType = "CREATE TYPE e_binary_position AS ENUM ('L','R','N');";

        DB::connection()->getPdo()->exec($createType);
        //DB::unprepared('CREATE PROCEDURE test() BEGIN SELECT * FROM user; END');

        $schema->create('tree_binary', function (ExtendedBlueprint $table) {
            //$table->integer('id',true,true);
            $table->integer('parent_id')->nullable();
            $table->integer('user_id');
            $table->enum_pg('bt_position','e_binary_position');
            $table->integer('depth')->nullable();
            $table->integer('l')->nullable();
            $table->integer('r')->nullable();
            $table->integer('refer_id')->nullable();
         //   $table->json('info')->nullable();
            $table->unique(['parent_id','user_id']);
            $table->unique(['parent_id','user_id','bt_position']);
            //$table->primary(['parent_id','user_id']);
            //   $table->dropIndex('geo_state_index'); // Drop basic index in 'state' from 'geo' table
            $table->index('parent_id');
            $table->index(['parent_id','user_id']);
            $table->timestamps();
        });

        //$this->seedInitialTree();
        $this->createPgSqlFunctions();

        DB::commit();
    }

    protected function createPgSqlFunctions()
    {

        $rawSQL = file_get_contents(__DIR__.'/../raw/binary/tree_binary_functions.sql');


        DB::connection()->getPdo()->exec($rawSQL);
    }
    
    protected function seedInitialTree()
    {
        DB::table('tree_binary')->insert([
            ['parent_id'=>0,'user_id'=>1,'bt_position'=>'N','depth'=>0],
            ['parent_id'=>1,'user_id'=>2,'bt_position'=>'L','depth'=>1],
            ['parent_id'=>1,'user_id'=>3,'bt_position'=>'R','depth'=>1],
            ['parent_id'=>2,'user_id'=>4,'bt_position'=>'L','depth'=>2],
            ['parent_id'=>2,'user_id'=>5,'bt_position'=>'R','depth'=>2],
            ['parent_id'=>3,'user_id'=>6,'bt_position'=>'R','depth'=>2],
            ['parent_id'=>4,'user_id'=>7,'bt_position'=>'L','depth'=>3],
            ['parent_id'=>5,'user_id'=>8,'bt_position'=>'L','depth'=>3],
            ['parent_id'=>5,'user_id'=>9,'bt_position'=>'R','depth'=>3],
            ['parent_id'=>3,'user_id'=>10,'bt_position'=>'L','depth'=>2],
        ]);
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

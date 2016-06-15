<?php

use Database\Schema\ExtendedBlueprint;
use Database\Schema\ExtendedPostrgresGrammer;
use Illuminate\Database\Schema\Blueprint;
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


        $createType = "DROP TYPE IF EXISTS e_binary_position";
        DB::connection()->getPdo()->exec($createType);
        $createType = "CREATE TYPE e_binary_position AS ENUM ('L','R','N');";

        DB::connection()->getPdo()->exec($createType);


        $schema->create('tree_binary', function (ExtendedBlueprint $table) {
            $table->integer('id',true,true);
            $table->integer('parent_id')->nullable();
            $table->integer('child_id');

            $table->enum_pg('bt_position','e_binary_position');

            //$table->enum('bt_position',['L','R','N']);
            $table->integer('l_child_id')->nullable();
            $table->integer('r_child_id')->nullable();
            $table->integer('refer_id')->nullable();
            $table->integer('depth')->nullable();
         //   $table->json('info')->nullable();
            $table->unique(['parent_id','child_id']);
            $table->unique(['parent_id','child_id','bt_position']);
            //$table->primary(['parent_id','child_id']);
            //   $table->dropIndex('geo_state_index'); // Drop basic index in 'state' from 'geo' table
            $table->index('parent_id');
            $table->index(['parent_id','child_id']);
            $table->nullableTimestamps();
        });

        $this->seedInitialTree();


        DB::commit();
    }

    protected function seedInitialTree()
    {
        DB::table('tree_binary')->insert([
            ['parent_id'=>0,'child_id'=>1,'bt_position'=>'N','depth'=>0],
            ['parent_id'=>1,'child_id'=>2,'bt_position'=>'L','depth'=>1],
            ['parent_id'=>1,'child_id'=>3,'bt_position'=>'R','depth'=>1],
            ['parent_id'=>2,'child_id'=>4,'bt_position'=>'L','depth'=>2],
            ['parent_id'=>2,'child_id'=>5,'bt_position'=>'R','depth'=>2],
            ['parent_id'=>3,'child_id'=>6,'bt_position'=>'R','depth'=>2],
            ['parent_id'=>4,'child_id'=>7,'bt_position'=>'L','depth'=>3],
            ['parent_id'=>5,'child_id'=>8,'bt_position'=>'L','depth'=>3],
            ['parent_id'=>5,'child_id'=>9,'bt_position'=>'R','depth'=>3],
            ['parent_id'=>3,'child_id'=>10,'bt_position'=>'L','depth'=>2],
        ]);
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $createType = "DROP TYPE IF EXISTS e_binary_position CASCADE";

        DB::connection()->getPdo()->exec($createType);
        Schema::drop('tree_binary');
    }
}

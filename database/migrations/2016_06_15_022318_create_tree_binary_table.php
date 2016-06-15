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

            
            //$table->enum('bt_position',['L','R','N']);
            $table->integer('l_child_id')->nullable();
            $table->integer('r_child_id')->nullable();
            $table->integer('refer_id')->nullable();
            $table->integer('depth')->nullable();
            $table->json('info')->nullable();
            $table->unique(['parent_id','child_id']);
            $table->unique(['parent_id','child_id','bt_position']);
            //$table->primary(['parent_id','child_id']);
         //   $table->dropIndex('geo_state_index'); // Drop basic index in 'state' from 'geo' table
            $table->index('parent_id');
            $table->index(['parent_id','child_id']);
            $table->nullableTimestamps();
            $table->enum_pg('bt_position','e_binary_position');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $createType = "DROP TYPE e_binary_position CASCADE";

        DB::connection()->getPdo()->exec($createType);
        Schema::drop('tree_binary');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stone_primary_classifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
        });

        Schema::create('stone_materials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
        });

        Schema::create('stones', function (Blueprint $table) {
            $table->string('id', 11)->primary();
            $table->string('locus_id', 5);
            $table->enum('code', ['GS', 'AR']);
            $table->unsignedTinyInteger('basket_no');
            $table->unsignedTinyInteger('artifact_no');
            $table->date('date_retrieved')->nullable();
            $table->string('field_description', 400)->nullable();
            $table->string('field_notes', 400)->nullable();
            $table->string('artifact_count', 10)->nullable();
            $table->string('square', 20)->nullable();
            $table->string('level_top', 20)->nullable();
            $table->string('level_bottom', 20)->nullable();
            $table->unsignedInteger('stone_primary_classification_id')->default(1);
            $table->unsignedInteger('material_id')->default(1);
            $table->string('description', 400)->nullable();
            $table->string('notes', 400)->nullable();
            $table->unsignedSmallInteger('weight')->nullable();
            $table->unsignedSmallInteger('length')->nullable();
            $table->unsignedSmallInteger('width')->nullable();
            $table->unsignedSmallInteger('depth')->nullable();
            $table->unsignedSmallInteger('thickness_min')->nullable();
            $table->unsignedSmallInteger('thickness_max')->nullable();
            $table->unsignedSmallInteger('perforation_diameter_max')->nullable();
            $table->unsignedSmallInteger('perforation_diameter_min')->nullable();
            $table->unsignedSmallInteger('perforation_depth')->nullable();
            $table->unsignedSmallInteger('diameter')->nullable();
            $table->unsignedSmallInteger('rim_diameter')->nullable();
            $table->unsignedSmallInteger('rim_thickness')->nullable();
            $table->unsignedSmallInteger('base_diameter')->nullable();
            $table->unsignedSmallInteger('base_thickness')->nullable();

            $table->foreign('locus_id')
                ->references('id')->on('loci')
                ->onUpdate('cascade');

            $table->unique(['locus_id', 'code', 'basket_no', 'artifact_no'], 'idx_unique_find');

            //by default delete of row in parent table is rejected if any refernece exists.
            $table->foreign('stone_primary_classification_id')
                ->references('id')->on('stone_primary_classifications')
                ->onUpdate('cascade');

            $table->foreign('material_id')
                ->references('id')->on('stone_materials')
                ->onUpdate('cascade');
        });

        Schema::create('stone_tag_groups', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 40);
            $table->boolean('multiple')->default(0);
        });

        Schema::create('stone_tags', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 50);
            $table->unsignedTinyInteger('tag_group_id');
            $table->unsignedSmallInteger('order_column');

            $table->foreign('tag_group_id')
                ->references('id')
                ->on('stone_tag_groups')
                ->onUpdate('cascade');
        });

        Schema::create('stone-stone_tags', function (Blueprint $table) {
            $table->string('item_id', 11);
            $table->foreign('item_id')->references('id')->on('stones')->onUpdate('cascade');

            $table->unsignedSmallInteger('tag_id')->unsigned();
            $table->foreign('tag_id')->references('id')->on('stone_tags')->onUpdate('cascade');

            $table->primary(['item_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //drop dependant tables first to avoid fk violations. 
        Schema::dropIfExists('stone_materials');
        Schema::dropIfExists('stone_primary_classifications');
        Schema::dropIfExists('stones');
    }
};

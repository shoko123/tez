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
        Schema::create('metal_primary_classifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
        });

        Schema::create('metal_materials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
        });

        Schema::create('metals', function (Blueprint $table) {
            $table->string('id', 11)->primary();
            $table->string('locus_id', 5);
            $table->enum('code', ['AR']);
            $table->unsignedTinyInteger('basket_no');
            $table->unsignedTinyInteger('artifact_no');
            $table->date('date_retrieved')->nullable();
            $table->string('field_description', 400)->nullable();
            $table->string('field_notes', 400)->nullable();
            $table->string('artifact_count', 10)->nullable();
            $table->string('square', 20)->nullable();
            $table->string('level_top', 20)->nullable();
            $table->string('level_bottom', 20)->nullable();
            //
            $table->unsignedInteger('material_id')->default(1);
            $table->unsignedInteger('metal_primary_classification_id')->default(1);
            $table->string('description', 400)->nullable();
            $table->string('measurements', 200)->nullable();
            $table->string('notes')->nullable();


            $table->foreign('locus_id')
                ->references('id')->on('loci')
                ->onUpdate('cascade');

            $table->unique(['locus_id', 'code', 'basket_no', 'artifact_no'], 'idx_unique_find');

            $table->foreign('metal_primary_classification_id')
                ->references('id')->on('metal_primary_classifications')
                ->onUpdate('cascade');

            $table->foreign('material_id')
                ->references('id')->on('metal_materials')
                ->onUpdate('cascade');
        });

        Schema::create('metal_tag_groups', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 40);
            $table->boolean('multiple')->default(0);
        });

        Schema::create('metal_tags', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 50);
            $table->unsignedTinyInteger('tag_group_id');
            $table->unsignedSmallInteger('order_column');

            $table->foreign('tag_group_id')
                ->references('id')
                ->on('metal_tag_groups')
                ->onUpdate('cascade');
        });

        Schema::create('metal-metal_tags', function (Blueprint $table) {
            $table->string('item_id', 11);
            $table->foreign('item_id')->references('id')->on('metals')->onUpdate('cascade');

            $table->unsignedSmallInteger('tag_id')->unsigned();
            $table->foreign('tag_id')->references('id')->on('metal_tags')->onUpdate('cascade');

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
        Schema::dropIfExists('metal_materials');
        Schema::dropIfExists('metal_primary_classifications');
        Schema::dropIfExists('metals');
        Schema::dropIfExists('metal-metal_tags');
        Schema::dropIfExists('metal_tags');
        Schema::dropIfExists('metal_tag_groups');
    }
};

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
        Schema::create('ceramic_primary_classifications', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();
            $table->string('name', 50);
        });

        Schema::create('ceramics', function (Blueprint $table) {
            $table->string('id', 11)->primary();
            $table->string('locus_id', 5);
            $table->enum('code', ['PT', 'AR']);
            $table->unsignedTinyInteger('basket_no');
            $table->unsignedTinyInteger('artifact_no');
            $table->date('date_retrieved')->nullable();
            $table->string('field_description', 400)->nullable();
            $table->string('field_notes', 400)->nullable();
            $table->string('square', 20)->nullable();
            $table->string('level_top', 20)->nullable();
            $table->string('level_bottom', 20)->nullable();
            $table->unsignedTinyInteger('primary_classification_id')->default(1);
            $table->enum('specialist', ['Unassigned', 'Tamar Shooval and Danny Rosenberg', 'Eliot Braun', 'Estelle Orrelle']);
            $table->string('periods', 250)->nullable();
            $table->string('specialist_description', 400)->nullable();

            $table->foreign('locus_id')
                ->references('id')->on('loci')
                ->onUpdate('cascade');

            $table->foreign('primary_classification_id')
                ->references('id')->on('ceramic_primary_classifications')
                ->onUpdate('cascade');

            $table->unique(['locus_id', 'code', 'basket_no', 'artifact_no'], 'idx_unique_find');
        });

        Schema::create('ceramic_tag_groups', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 40);
            $table->boolean('multiple')->default(0);
        });

        Schema::create('ceramic_tags', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 50);
            $table->unsignedTinyInteger('tag_group_id');
            $table->unsignedSmallInteger('order_column');

            $table->foreign('tag_group_id')
                ->references('id')
                ->on('ceramic_tag_groups')
                ->onUpdate('cascade');
        });

        Schema::create('ceramic-ceramic_tags', function (Blueprint $table) {
            $table->string('item_id', 11);
            $table->foreign('item_id')->references('id')->on('ceramics')->onUpdate('cascade');

            $table->unsignedSmallInteger('tag_id')->unsigned();
            $table->foreign('tag_id')->references('id')->on('ceramic_tags')->onUpdate('cascade');

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
        Schema::dropIfExists('ceramic');
    }
};

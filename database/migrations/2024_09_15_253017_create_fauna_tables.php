<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fauna_primary_taxa', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 50);
        });

        Schema::create('fauna_scopes', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 50);
        });

        Schema::create('fauna_materials', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 50);
        });

        Schema::create('fauna', function (Blueprint $table) {
            $table->string('id', 11)->primary();
            $table->string('locus_id', 5);
            $table->enum('code', ['LB', 'AR']);
            $table->unsignedTinyInteger('basket_no');
            $table->unsignedTinyInteger('artifact_no');
            //
            $table->date('date_retrieved')->nullable();
            $table->unsignedMediumInteger('weight')->nullable();
            $table->string('field_description')->nullable();
            //
            $table->unsignedTinyInteger('primary_taxon_id')->default(1);
            $table->unsignedTinyInteger('scope_id')->default(1);
            $table->unsignedTinyInteger('material_id')->default(1);
            //
            $table->string('taxa', 400)->nullable();
            $table->string('bone', 400)->nullable();
            $table->enum('symmetry', ['Unassigned', 'Irrelevant', 'Unknown', 'Left', 'Right']);
            $table->string('d_and_r', 30)->nullable();
            $table->string('age', 50)->nullable();
            $table->string('breakage', 50)->nullable();
            $table->string('butchery', 100)->nullable();
            $table->string('burning', 100)->nullable();
            $table->enum('weathering', ['Unassigned', '0', '1', '2', '3', '4', '5']);
            $table->string('other_bsm', 200)->nullable();
            $table->string('specialist_notes', 200)->nullable();
            $table->string('measured', 1)->nullable();

            $table->foreign('locus_id')
                ->references('id')->on('loci')
                ->onUpdate('cascade');

            $table->unique(['locus_id', 'code', 'basket_no', 'artifact_no'], 'idx_unique_find');

            $table->foreign('primary_taxon_id')
                ->references('id')->on('fauna_primary_taxa')
                ->onUpdate('cascade');

            $table->foreign('scope_id')
                ->references('id')->on('fauna_scopes')
                ->onUpdate('cascade');

            $table->foreign('material_id')
                ->references('id')->on('fauna_materials')
                ->onUpdate('cascade');
        });

        Schema::create('fauna_tag_groups', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 40);
            $table->boolean('multiple')->default(0);
        });

        Schema::create('fauna_tags', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 50);
            $table->unsignedTinyInteger('tag_group_id');
            $table->unsignedSmallInteger('order_column');

            $table->foreign('tag_group_id')
                ->references('id')
                ->on('fauna_tag_groups')
                ->onUpdate('cascade');
        });

        Schema::create('fauna-fauna_tags', function (Blueprint $table) {
            $table->string('item_id', 11);
            $table->foreign('item_id')->references('id')->on('fauna')->onUpdate('cascade');
            //
            $table->unsignedSmallInteger('tag_id')->unsigned();
            $table->foreign('tag_id')->references('id')->on('fauna_tags')->onUpdate('cascade');
            //
            $table->primary(['item_id', 'tag_id']);
        });

        Schema::create('fauna_onps', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->enum('group_label', ['Measurements']);
            $table->string('label', 50);
            $table->unsignedTinyInteger('order_column');
            //
            $table->unique(['group_label', 'label']);
        });

        Schema::create('fauna-fauna_onps', function (Blueprint $table) {
            $table->string('item_id', 11);
            $table->unsignedSmallInteger('onp_id');
            $table->unsignedSmallInteger('value');

            $table->foreign('onp_id')->references('id')->on('fauna_onps')->onUpdate('cascade');
            $table->foreign('item_id')->references('id')->on('fauna')->onUpdate('cascade');
            $table->primary(['item_id', 'onp_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {}
};

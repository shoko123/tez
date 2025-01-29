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
        Schema::create('lithics', function (Blueprint $table) {
            $table->string('id', 11)->primary();
            $table->string('locus_id', 5);
            $table->enum('code', ['FL', 'AR']);
            $table->unsignedTinyInteger('basket_no');
            $table->unsignedTinyInteger('artifact_no');
            $table->date('date_retrieved')->nullable();
            $table->unsignedSmallInteger('weight')->nullable();
            $table->string('field_description', 100)->nullable();
            $table->string('registration_notes', 100)->nullable();
            $table->string('specialist_notes', 100)->nullable();

            $table->foreign('locus_id')
                ->references('id')->on('loci')
                ->onUpdate('cascade');

            $table->unique(['locus_id', 'code', 'basket_no', 'artifact_no'], 'idx_unique_find');
        });

        Schema::create('lithic_tag_groups', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 40);
            $table->boolean('multiple')->default(0);
        });

        Schema::create('lithic_tags', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 50);
            $table->unsignedTinyInteger('tag_group_id');
            $table->unsignedSmallInteger('order_column');

            $table->foreign('tag_group_id')
                ->references('id')
                ->on('lithic_tag_groups')
                ->onUpdate('cascade');
        });

        Schema::create('lithic-lithic_tags', function (Blueprint $table) {
            $table->string('item_id', 11);
            $table->foreign('item_id')->references('id')->on('lithics')->onUpdate('cascade');

            $table->unsignedSmallInteger('tag_id')->unsigned();
            $table->foreign('tag_id')->references('id')->on('lithic_tags')->onUpdate('cascade');

            $table->primary(['item_id', 'tag_id']);
        });

        Schema::create('lithic_onps', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->enum('group_label', ['Count']);
            $table->string('label', 50);
            $table->unsignedTinyInteger('order_column');
        });

        Schema::create('lithic-lithic_onps', function (Blueprint $table) {
            $table->string('item_id', 11);
            $table->unsignedSmallInteger('onp_id');
            $table->unsignedSmallInteger('value');
            //
            $table->foreign('onp_id')->references('id')->on('lithic_onps')->onUpdate('cascade');
            $table->foreign('item_id')->references('id')->on('lithics')->onUpdate('cascade');
            $table->primary(['item_id', 'onp_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lithics');
        Schema::dropIfExists('lithic_tag_groups');
        Schema::dropIfExists('lithic_tags');
        Schema::dropIfExists('lithic-lithic_tags');
        Schema::dropIfExists('lithic_onps');
        Schema::dropIfExists('lithic-lithic_onps');
    }
};

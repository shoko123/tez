<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('loci', function (Blueprint $table) {
            $table->string('id', 5)->primary();
            $table->string('area_id', 1);
            $table->string('season_id', 1);
            $table->unsignedInteger('locus_no');
            $table->string('square', 20)->nullable();
            $table->date('date_opened')->nullable();
            $table->date('date_closed')->nullable();
            $table->string('level_opened', 20)->nullable();
            $table->string('level_closed', 20)->nullable();
            $table->string('locus_above', 50)->nullable();
            $table->string('locus_below', 50)->nullable();
            $table->string('locus_co_existing', 50)->nullable();
            $table->string('description', 1000)->nullable();
            $table->string('deposit', 500)->nullable();
            $table->string('registration_notes', 500)->nullable();
            $table->string('clean', 1)->nullable();

            $table->foreign('area_id')
                ->references('id')->on('areas')
                ->onUpdate('cascade');

            $table->foreign('season_id')
                ->references('id')->on('seasons')
                ->onUpdate('cascade');
        });

        Schema::create('locus_tag_groups', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 40);
            $table->boolean('multiple')->default(0);
        });

        Schema::create('locus_tags', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 50);
            $table->unsignedTinyInteger('tag_group_id');
            $table->unsignedSmallInteger('order_column');

            $table->foreign('tag_group_id')
                ->references('id')
                ->on('locus_tag_groups')
                ->onUpdate('cascade');
        });

        Schema::create('locus-locus_tags', function (Blueprint $table) {
            $table->string('item_id', 5);
            $table->foreign('item_id')->references('id')->on('loci')->onUpdate('cascade');

            $table->unsignedSmallInteger('tag_id')->unsigned();
            $table->foreign('tag_id')->references('id')->on('locus_tags')->onUpdate('cascade');

            $table->primary(['item_id', 'tag_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('locus-locus_tags');
        Schema::dropIfExists('locus_tag_groups');
        Schema::dropIfExists('locus_tags');
        Schema::dropIfExists('loci');
    }
};

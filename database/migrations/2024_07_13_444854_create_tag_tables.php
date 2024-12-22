<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tag_groups', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();
            $table->string('name', 40);
            $table->boolean('multiple')->default(0);
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->string('name', 40);
            $table->unsignedTinyInteger('tag_group_id');
            $table->unsignedInteger('order_column')->nullable();

            $table->foreign('tag_group_id')->references('id')->on('tag_groups')->onUpdate('cascade');
        });

        Schema::create('taggables', function (Blueprint $table) {
            $table->string('taggable_type', 10);
            $table->string('taggable_id', 11);
            $table->unsignedInteger('tag_id');

            $table->primary(['taggable_type', 'taggable_id', 'tag_id']);

            $table->foreign('tag_id')->references('id')->on('tags')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::drop('taggables');
        Schema::drop('tags');
        Schema::drop('tag_groups');
    }
};

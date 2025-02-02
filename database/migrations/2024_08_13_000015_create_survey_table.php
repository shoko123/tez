<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('survey', function (Blueprint $table) {
            $table->string('id', 4)->primary();
            $table->string('area_id', 1);
            $table->unsignedSmallInteger('feature_no')->default(1);
            $table->date('surveyed_date')->nullable();
            $table->unsignedSmallInteger('elevation')->nullable();
            $table->string('next_to', 50)->nullable();
            $table->string('description', 200)->nullable();
            $table->string('notes', 100)->nullable();

            $table->foreign('area_id')
                ->references('id')->on('areas')
                ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('survey');
    }
};

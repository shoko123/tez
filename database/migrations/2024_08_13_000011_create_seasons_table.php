<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->string('id', 1)->primary();
            $table->string('description', 2000)->nullable();
            $table->string('staff', 2000)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('seasons');
    }
};

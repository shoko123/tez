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

        Schema::create('fauna', function (Blueprint $table) {
            $table->string('id', 11)->primary();
            $table->string('locus_id', 5);
            $table->string('code', 2)->nullable();
            $table->unsignedTinyInteger('basket_no');
            $table->unsignedTinyInteger('artifact_no');
            $table->unsignedTinyInteger('primary_taxon_id');
            $table->string('taxa', 400)->nullable();
            $table->string('bone', 400)->nullable();
            $table->string('side', 10)->nullable();
            $table->string('d_and_r', 30)->nullable();
            $table->string('age', 50)->nullable();
            $table->string('breakage', 50)->nullable();
            $table->string('butchery', 20)->nullable();
            $table->string('butchery_description', 100)->nullable();
            $table->string('burning', 20)->nullable();
            $table->string('burning_description', 400)->nullable();
            $table->string('weathering', 50)->nullable();
            $table->string('other_bsm', 200)->nullable();
            $table->string('notes', 200)->nullable();
            $table->string('measured', 200)->nullable();
            $table->string('GL', 8)->nullable();
            $table->string('Glpe', 8)->nullable();
            $table->string('GLl', 8)->nullable();
            $table->string('GLP', 8)->nullable();
            $table->string('Bd', 8)->nullable();
            $table->string('BT', 8)->nullable();
            $table->string('Dd', 8)->nullable();
            $table->string('BFd', 8)->nullable();
            $table->string('Bp', 8)->nullable();
            $table->string('Dp', 8)->nullable();
            $table->string('SD', 8)->nullable();
            $table->string('HTC', 8)->nullable();
            $table->string('Dl', 8)->nullable();
            $table->string('DEM', 8)->nullable();
            $table->string('DVM', 8)->nullable();
            $table->string('WCM', 8)->nullable();
            $table->string('DEL', 8)->nullable();
            $table->string('DVL', 8)->nullable();
            $table->string('WCL', 8)->nullable();
            $table->string('LD', 8)->nullable();
            $table->string('DLS', 8)->nullable();
            $table->string('LG', 8)->nullable();
            $table->string('BG', 8)->nullable();
            $table->string('DID', 8)->nullable();
            $table->string('BFcr', 8)->nullable();
            $table->string('GD', 8)->nullable();
            $table->string('GB', 8)->nullable();
            $table->string('BF', 8)->nullable();
            $table->string('LF', 8)->nullable();
            $table->string('GLm', 8)->nullable();
            $table->string('GH', 8)->nullable();

            $table->foreign('locus_id')
                ->references('id')->on('loci')
                ->onUpdate('cascade');

            $table->unique(['locus_id', 'code', 'basket_no', 'artifact_no'], 'idx_unique_find');

            $table->foreign('primary_taxon_id')
                ->references('id')->on('fauna_primary_taxa')
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

        /*
        Schema::create('fauna-fauna_tags', function (Blueprint $table) {
            $table->string('item_id', 11);
            $table->foreign('item_id')->references('id')->on('fauna')->onUpdate('cascade');

            $table->unsignedSmallInteger('tag_id')->unsigned();
            $table->foreign('tag_id')->references('id')->on('fauna_tags')->onUpdate('cascade');

            $table->primary(['item_id', 'tag_id']);
        });
        */

        Schema::create('fauna_onps', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedTinyInteger('order_column');
            $table->string('label', 50);
            $table->string('unit', 5)->nullable()->default(null);
            $table->unsignedSmallInteger('divide_by')->default(1);
        });
        /*
        Schema::create('fauna-fauna_onps', function (Blueprint $table) {
            $table->string('item_id', 11);
            $table->unsignedSmallInteger('num_id');
            $table->unsignedSmallInteger('value');

            $table->foreign('num_id')->references('id')->on('fauna_onps')->onUpdate('cascade');
            $table->foreign('item_id')->references('id')->on('faunas')->onUpdate('cascade');
            $table->primary(['item_id', 'num_id']);
        });
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {}
};

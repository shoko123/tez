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
        Schema::create('fauna_taxa', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 50);
        });

        Schema::create('fauna_elements', function (Blueprint $table) {
            $table->tinyincrements('id');
            $table->string('name', 50);
        });

        /*
        Schema::create('fauna', function (Blueprint $table) {

            $table->string('id', 11)->primary();
            $table->string('locus_id', 5);
            $table->string('code', 2)->nullable();
            $table->unsignedTinyInteger('basket_no');
            $table->unsignedTinyInteger('artifact_no');
            $table->date('date_retrieved')->nullable();
            $table->string('field_description', 400)->nullable();
            $table->string('field_notes', 400)->nullable();
            $table->string('artifact_count', 10)->nullable();
            $table->string('square', 20)->nullable();
            $table->string('level_top', 20)->nullable();
            $table->string('level_bottom', 20)->nullable();
            $table->string('description', 400)->nullable();
            $table->string('notes', 200)->nullable();
            $table->unsignedTinyInteger('fauna_taxon_id')->dafault(1);
            $table->unsignedTinyInteger('fauna_element_id')->dafault(1);
            $table->boolean('has_butchery_evidence')->nullable();
            $table->boolean('has_burning_evidence')->nullable();
            $table->boolean('has_other_bsm_evidence')->nullable();
            $table->boolean('is_fused')->nullable();
            $table->boolean('is_left')->nullable();
            $table->string('d_and_r', 50)->nullable();
            $table->unsignedTinyInteger('weathering')->nullable();
            $table->string('age', 50)->nullable();
            $table->string('breakage', 50)->nullable();

            $table->Decimal('GL', 4, 1)->nullable();
            $table->Decimal('Glpe', 4, 1)->nullable();
            $table->Decimal('GLl', 4, 1)->nullable();
            $table->Decimal('GLP', 4, 1)->nullable();
            $table->Decimal('Bd', 4, 1)->nullable();
            $table->Decimal('BT', 4, 1)->nullable();
            $table->Decimal('Dd', 4, 1)->nullable();
            $table->Decimal('BFd', 4, 1)->nullable();
            $table->Decimal('Bp', 4, 1)->nullable();
            $table->Decimal('Dp', 4, 1)->nullable();
            $table->Decimal('SD', 4, 1)->nullable();
            $table->Decimal('HTC', 4, 1)->nullable();
            $table->Decimal('Dl', 4, 1)->nullable();
            $table->Decimal('DEM', 4, 1)->nullable();
            $table->Decimal('DVM', 4, 1)->nullable();
            $table->Decimal('WCM', 4, 1)->nullable();
            $table->Decimal('DEL', 4, 1)->nullable();
            $table->Decimal('DVL', 4, 1)->nullable();
            $table->Decimal('WCL', 4, 1)->nullable();
            $table->Decimal('LD', 4, 1)->nullable();
            $table->Decimal('DLS', 4, 1)->nullable();
            $table->Decimal('LG', 4, 1)->nullable();
            $table->Decimal('BG', 4, 1)->nullable();
            $table->Decimal('DID', 4, 1)->nullable();
            $table->Decimal('BFcr', 4, 1)->nullable();
            $table->Decimal('GD', 4, 1)->nullable();
            $table->Decimal('GB', 4, 1)->nullable();
            $table->Decimal('BF', 4, 1)->nullable();
            $table->Decimal('LF', 4, 1)->nullable();
            $table->Decimal('GLm', 4, 1)->nullable();
            $table->Decimal('GH', 4, 1)->nullable();


            $table->foreign('locus_id')
                ->references('id')->on('loci')
                ->onUpdate('cascade');

            $table->unique(['locus_id', 'code', 'basket_no', 'artifact_no'], 'idx_unique_find');

            $table->foreign('fauna_taxon_id')
                ->references('id')->on('fauna_taxa')
                ->onUpdate('cascade');

            $table->foreign('fauna_element_id')
                ->references('id')->on('fauna_elements')
                ->onUpdate('cascade');
        });
*/
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

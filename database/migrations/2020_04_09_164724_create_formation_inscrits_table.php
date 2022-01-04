<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormationInscritsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscrit_formation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('formation_id')->unsigned();
            $table->bigInteger('inscrit_id')->unsigned();
            $table->bigInteger('infos_id')->unsigned();
            $table->date('date_ajout');
            $table->foreign('formation_id')->references('id')->on('formations')->cascadeOnDelete();
            $table->foreign('inscrit_id')->references('id')->on('inscrits')->cascadeOnDelete();
            $table->foreign('infos_id')->references('id')->on('inscrit_infos')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inscrit_formation');
    }
}

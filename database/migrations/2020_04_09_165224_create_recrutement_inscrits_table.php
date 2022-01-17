<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecrutementInscritsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscrit_recrutement', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('recrutement_id')->unsigned();
            $table->bigInteger('inscrit_id')->unsigned();
            $table->date('date_ajout');
            $table->boolean('selection')->default(0);

            $table->foreign('recrutement_id')->references('id')->on('recrutements')->cascadeOnDelete();
            $table->foreign('inscrit_id')->references('id')->on('inscrits')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inscrit_recrutement');
    }
}

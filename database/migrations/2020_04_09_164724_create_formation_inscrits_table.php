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
            $table->date('date_ajout');
            $table->date('date_rappel')->nullable();
            $table->unsignedBigInteger('rappel_user_id')->nullable();
            $table->string('rappel_resultat', 100)->nullable();
            $table->date('date_rdv')->nullable();
            $table->unsignedBigInteger('rdv_user_id')->nullable();
            $table->boolean('validation_rdv')->nullable();

            $table->foreign('formation_id')->references('id')->on('formations')->cascadeOnDelete();
            $table->foreign('inscrit_id')->references('id')->on('inscrits')->cascadeOnDelete();
            $table->foreign('rappel_user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('rdv_user_id')->references('id')->on('users')->cascadeOnDelete();
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

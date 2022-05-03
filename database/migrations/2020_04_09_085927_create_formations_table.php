<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom');
            $table->string('session')->nullable();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->integer('min_stagiaires');
            $table->integer('max_stagiaires');
            $table->integer('nbre_heures')->default(0);
            $table->bigInteger('salle_id')->unsigned();
            $table->float('prix')->default(0.00)->nullable();
            $table->text('commentaire_formation')->nullable();
            $table->string('statut');
            $table->bigInteger('user_id')->unsigned();
            $table->string('abreviation', 15);
            $table->integer('duree_rgpd');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formations');
    }
}

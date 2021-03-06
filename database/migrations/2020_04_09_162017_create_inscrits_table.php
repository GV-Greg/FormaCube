<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscritsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscrits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 255);
            $table->string('prenom', 255);
            $table->enum('genre', ['homme', 'femme', '3e genre'])->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('rue', 255)->nullable();
            $table->integer('numero')->nullable();
            $table->string('boite', 10)->nullable();
            $table->bigInteger('ville_id')->unsigned()->nullable();
            $table->string('email', 255)->nullable();
            $table->string('tel', 20)->nullable();
            $table->string('gsm', 20)->nullable();
            $table->text('commentaire_inscrit')->nullable();
            $table->boolean('newsletter')->default(false);
            $table->boolean('prospect');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inscrits');
    }
}

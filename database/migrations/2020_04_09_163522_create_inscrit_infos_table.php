<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscritInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscrit_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('pouvsubinfos_id')->unsigned();
            $table->bigInteger('inscrit_id')->unsigned();
            $table->integer('age')->nullable();
            $table->integer('num_national')->nullable();
            $table->enum('statut_legal', ['célibataire', 'marié·e', 'cohabitant'])->nullable();
            $table->string('diplome')->nullable();
            $table->integer('duree_chomage')->nullable();
            $table->string('source_info')->nullable();
            $table->enum('groupe_social', ['réfugié', 'sociale', 'médicale'])->nullable();
            $table->foreign('pouvsubinfos_id')->references('id')->on('pouvsub_infos')->cascadeOnDelete();
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
        Schema::dropIfExists('inscrit_infos');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePouvSubsTable extends Migration
{

    public function up()
    {
        Schema::create('pouvsubs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom');
            $table->string('logo')->nullable();
            $table->boolean('adresse');
            $table->boolean('date_naissance');
            $table->boolean('age');
            $table->boolean('email');
            $table->boolean('num_national');
            $table->boolean('statut_legal');
            $table->boolean('diplome');
            $table->boolean('duree_chomage');
            $table->boolean('source_info');
            $table->boolean('groupe_social');
            $table->timestamps();
        });

        Schema::create('pouvsub_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('formation_id')->unsigned();
            $table->bigInteger('pouvsub_id')->unsigned();
            $table->boolean('adresse');
            $table->boolean('date_naissance');
            $table->boolean('age');
            $table->boolean('email');
            $table->boolean('num_national');
            $table->boolean('statut_legal');
            $table->boolean('diplome');
            $table->boolean('duree_chomage');
            $table->boolean('source_info');
            $table->boolean('groupe_social');

            $table->foreign('formation_id')->references('id')->on('formations')->cascadeOnDelete();
            $table->foreign('pouvsub_id')->references('id')->on('pouvsubs')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pouvsub_infos');
        Schema::dropIfExists('pouvsubs');
    }
}

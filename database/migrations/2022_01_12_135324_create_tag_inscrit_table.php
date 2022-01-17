<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagInscritTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_inscrit', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('inscrit_id')->unsigned();
            $table->bigInteger('tag_id')->unsigned();

            $table->foreign('inscrit_id')->references('id')->on('inscrits')->cascadeOnDelete();
            $table->foreign('tag_id')->references('id')->on('tags')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tag_inscrit');
    }
}

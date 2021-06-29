<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRappelsToInscritFormationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inscrit_formation', function (Blueprint $table) {
            $table->date('date_rappel')->nullable();
            $table->unsignedBigInteger('rappel_user_id')->nullable();
            $table->foreign('rappel_user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inscrit_formation', function (Blueprint $table) {
            $table->dropForeign(['rappel_user_id']);
        });
        Schema::dropIfExists('inscrit_formation');
    }
}

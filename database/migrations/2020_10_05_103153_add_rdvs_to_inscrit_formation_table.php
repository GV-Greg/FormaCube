<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRdvsToInscritFormationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inscrit_formation', function (Blueprint $table) {
            $table->date('date_rdv')->nullable();
            $table->unsignedBigInteger('rdv_user_id')->nullable();
            $table->foreign('rdv_user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->boolean('validation_rdv')->nullable();
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
            $table->dropForeign(['rdv_user_id']);
            $table->dropColumn(['date_rdv', 'rdv_user_id']);
        });
    }
}

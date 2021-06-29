<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInscritFormationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inscrit_formation', function (Blueprint $table) {
            $table->string('rappel_resultat', 100)->nullable()->after('rappel_user_id');
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
            $table->dropColumn(['rappel_resultat']);
        });
    }
}

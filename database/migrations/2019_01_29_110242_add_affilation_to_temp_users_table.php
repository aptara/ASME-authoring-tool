<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAffilationToTempUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp__users', function (Blueprint $table) {
            $table->string('professional_affiliation')->nullable();
            $table->string('asme_affiliation')->nullable();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp__users', function (Blueprint $table) {
            $table->dropColumn(['professional_affiliation','asme_affiliation']);
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAffilationToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->after('email');
            $table->string('last_name')->after('first_name');
            $table->string('professional_affiliation')->nullable()->after('last_name');;
            $table->string('asme_affiliation')->nullable()->after('professional_affiliation');;
            $table->dropColumn(['name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name','last_name','professional_affiliation','asme_affiliation']);
            $table->string('name');
        });
    }
}

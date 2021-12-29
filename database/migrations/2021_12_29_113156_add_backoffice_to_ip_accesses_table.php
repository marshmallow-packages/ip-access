<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBackofficeToIpAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ip_accesses', function (Blueprint $table) {
            $table->after('ip_address_v6', function ($table) {
                $table->boolean('backoffice_access')->default(true);
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ip_accesses', function (Blueprint $table) {
            $table->dropColumn('backoffice_access');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogColumnToTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->json('status_log')->after('updated_at')->nullable();
            $table->string('status')->after('unique_id')->nullable()->default('GENERATED');
            $table->dropColumn('validated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('status_log');
            $table->dropColumn('status');
            $table->boolean('validated')->nullable()->default(false);
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCustomerWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_wallets', function (Blueprint $table) {
            $table->dropColumn('subscription_plan_id');
            $table->dropIndex('customer_subscription_plan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_wallets', function (Blueprint $table) {
            $table->integer('subscription_plan_id')->after('customer_id');
            $table->unique(["customer_id", "subscription_plan_id"], 'customer_subscription_plan');
        });
    }
}

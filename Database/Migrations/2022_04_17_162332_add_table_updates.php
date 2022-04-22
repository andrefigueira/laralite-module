<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableUpdates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_wallets', function (Blueprint $table) {
            $table->integer('subscription_plan_id')->after('customer_id');
            $table->unique(["customer_id", "subscription_plan_id"], 'customer_subscription_plan');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->integer('default_credit_amount')->default(0)->after('image');
            $table->integer('default_initial_credit_amount')->default(0)->after('default_credit_amount');
        });

        Schema::table('subscription_prices', function (Blueprint $table) {
            $table->integer('credit_amount')->default(0)->after('name');
            $table->integer('initial_credit_amount')->default(0)->after('name');
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
            $table->dropIndex('customer_subscription_plan');
            $table->dropColumn('subscription_plan_id');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('default_credit_amount');
            $table->dropColumn('default_initial_credit_amount');
        });

        Schema::table('subscription_prices', function (Blueprint $table) {
            $table->dropColumn('credit_amount');
            $table->dropColumn('initial_credit_amount');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCustomerSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_subscriptions', function (Blueprint $table) {
            $table->dropIndex('customer_subscription');
            $table->dropColumn('subscription_id');
            $table->integer('price_id')->after('customer_id');
            $table->integer('agreed_price')->nullable()->after('price_id');
            $table->unique(["customer_id", "price_id"], 'customer_subscription_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_subscriptions', function (Blueprint $table) {
            $table->integer('subscription_id')->after('customer_id');
            $table->unique(["customer_id", "subscription_id"], 'customer_subscription');
            $table->dropIndex('customer_subscription_price');
            $table->dropColumn('price_id');
            $table->dropColumn('agreed_price');
        });
    }
}

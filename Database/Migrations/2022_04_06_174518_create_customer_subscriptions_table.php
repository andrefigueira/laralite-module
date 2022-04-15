<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('subscription_id');
            $table->dateTime('expiry_date');
            $table->unique(["customer_id", "subscription_id"], 'customer_subscription');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_subscriptions');
    }
}

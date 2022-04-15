<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubscriptionPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_prices', function (Blueprint $table) {
            $table->id();
            $table->integer('subscription_id');
            $table->string('price');
            $table->string('name')->nullable();
            $table->json('meta_data')->nullable();
            $table->string('recurring_period');

            $table->timestamps();
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_prices');
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->integer('price');
        });
    }
}

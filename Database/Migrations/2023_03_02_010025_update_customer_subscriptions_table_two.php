<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCustomerSubscriptionsTableTwo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('customer_subscriptions', static function (Blueprint $table) {
            $table->integer('subscription_id')->after('customer_id');
            $table->string('unique_id')->after('id');
            $table->string('start_date')->nullable()->after('agreed_price');
            $table->unique('unique_id','subscription_unique_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('customer_subscriptions', static function (Blueprint $table) {
            $table->dropColumn('subscription_id');
            $table->dropColumn('start_date');
            $table->dropColumn('unique_id');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_action_log', static function (Blueprint $table) {
            $table->id();
            $table->integer('payment_id');
            $table->string('action_type');
            $table->string('result_status');
            $table->json('payment_processor_result')->nullable();
            $table->json('meta_data')->nullable();
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
        Schema::dropIfExists('payment_action_log');
    }
}

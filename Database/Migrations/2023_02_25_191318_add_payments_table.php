<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Laralite\Models\Payment;

class AddPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('payments', static function (Blueprint $table) {
            $table->id();
            $table->string('ext_id')->nullable();
            $table->json('meta_data')->nullable();
            $table->json('payment_processor_result')->nullable();
            $table->integer('payable_id')->nullable();
            $table->string('payable_type')->nullable();
            $table->string('type')->default(1);
            $table->string('status')->default(Payment::STATUS_CREATED);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
}

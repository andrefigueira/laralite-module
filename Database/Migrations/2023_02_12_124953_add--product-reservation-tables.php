<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductReservationTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('booking_slots', static function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->dateTime('slot_date_time');
            $table->unique(['product_id', 'slot_date_time'], 'product_id_slot_time');
            $table->timestamps();
        });

        Schema::create('booking_reservations', static function (Blueprint $table) {
            $table->id();
            $table->integer('booking_slot_id');
            $table->integer('order_id');
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
        Schema::dropIfExists('booking_reservations');
        Schema::dropIfExists('booking_slots');
    }
}

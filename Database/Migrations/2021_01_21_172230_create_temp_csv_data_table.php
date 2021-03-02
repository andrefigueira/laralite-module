<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempCsvDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_csv_data', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->unsigned()->nullable();
            $table->string('email');
            $table->string('financial_status');
            $table->string('paid_at');
            $table->string('fulfillment_status');
            $table->string('fulfilled_at');
            $table->string('currency');
            $table->string('subtotal');
            $table->string('shipping');
            $table->string('taxes');
            $table->string('amount_refunded');
            $table->string('total');
            $table->string('discount_code');
            $table->string('discount_amount');
            $table->string('shipping_method');
            $table->string('created_at');
            $table->string('lineitem_quantity');
            $table->string('lineitem_name');
            $table->string('lineitem_price');
            $table->string('lineitem_sku');
            $table->string('lineitem_variant');
            $table->string('lineitem_requires_shipping');
            $table->string('lineitem_taxable');
            $table->string('lineitem_fulfillment_status');
            $table->string('billing_name');
            $table->string('billing_address_1');
            $table->string('billing_address_2');
            $table->string('billing_city');
            $table->string('billing_zip');
            $table->string('billing_province');
            $table->string('billing_country');
            $table->string('billing_phone');
            $table->string('shipping_name');
            $table->string('shipping_address_1');
            $table->string('shipping_address_2');
            $table->string('shipping_city');
            $table->string('shipping_zip');
            $table->string('shipping_province');
            $table->string('shipping_country');
            $table->string('shipping_phone');
            $table->string('cancelled_at');
            $table->string('private_notes');
            $table->string('channel_type');
            $table->string('channel_name');
            $table->string('channel_order_number');
            $table->string('ga_tax');
            $table->string('payment_method');
            $table->string('payment_reference');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_csv_data');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('api_user_id');
            $table->bigInteger('hotel_id');
            $table->text('order_id');
            $table->dateTime('order_date');
            $table->text('address');
            $table->string('alternate_mobile', 14)->nullable();
            $table->text('customer_instructions')->nullable();
            $table->string('order_status')->default('received');
            $table->string('gst')->default('0');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name');
            $table->string('email')->unique()->nullable();
            $table->string('mobile',14)->unique();
            $table->string('password');
            $table->string('password_plain');
            $table->text('address');
            $table->integer('hotel_category');
            $table->string('lat');
            $table->string('long');
            $table->text('hotel_image')->nullable();
            $table->boolean('active');
            $table->text('fcm_token')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('hotels');
    }
}

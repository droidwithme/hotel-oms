.<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateApiUsersTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('api_users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('mobile', 14)->unique();
                $table->string('password');
                $table->string('password_plain');
                $table->text('address');
                $table->string('lat')->nullable();
                $table->string('long')->nullable();
                $table->string('profile_picture_path')->nullable();
                $table->string('verification_code')->nullable();
                $table->boolean('verified')->default(false);
                $table->boolean('active')->default(false);
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
            Schema::dropIfExists('api_users');
        }
    }

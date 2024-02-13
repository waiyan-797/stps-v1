<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('distance', 8, 2);
            $table->string('duration');
            $table->string('waiting_time');
            $table->decimal('normal_fee', 8, 0)->nullable();
            $table->decimal('waiting_fee', 8, 0)->nullable();
            $table->decimal('extra_fee', 8, 0)->nullable();
            $table->decimal('total_cost', 8, 0)->nullable();
            $table->decimal('lat',10,8)->nullable();
            $table->decimal('lng',11,8)->nullable();
            $table->decimal('end_lat',10,8)->nullable();
            $table->decimal('end_lng',11,8)->nullable();
            $table->enum('status',['pending','accepted','canceled','completed'])->default('pending');
            $table->string('start_address')->nullable();
            $table->string('end_address')->nullable();
            $table->integer('driver_id')->nullable();
            $table->enum('cartype',[1,2,3]);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('travel');
    }
};

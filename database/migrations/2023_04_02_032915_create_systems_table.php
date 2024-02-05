<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('systems', function (Blueprint $table) {
            $table->id();
            $table->decimal('balance', 10, 0);
            $table->decimal('normal_fee', 10, 0);
            $table->decimal('initial_fee', 10, 0);
            $table->decimal('waiting_fee', 10, 0);
            $table->decimal('commission_fee', 10, 0);
            $table->string('app_link')->nullable();
            $table->timestamps();
        });

        DB::table('systems')->insert([
            'balance' => 0.00,
            'normal_fee' => 1000.00,
            'initial_fee' => 1000.00,
            'waiting_fee' => 500.00,
            'commission_fee' => 200.00
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('systems');
    }
};

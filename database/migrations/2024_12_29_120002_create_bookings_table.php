<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration {
    public function up() {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->foreignId('agent_id')->constrained();
            $table->timestamp('start_datetime');
            $table->timestamp('end_datetime');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable(); 
        });
    }

    public function down() {
        Schema::dropIfExists('bookings');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration {
    public function up() {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('personal_code')->unique(); 
            $table->string('name'); 
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable(); 
        });
    }

    public function down() {
        Schema::dropIfExists('agents');
    }
}

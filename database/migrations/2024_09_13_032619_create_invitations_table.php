<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->references("id")->on("events")->constrained()->onDelete('cascade');
            $table->foreignId('sender_id')->references("id")->on("users")->constrained()->onDelete('cascade');
            $table->foreignId('receiver_id')->references("id")->on("users")->constrained()->onDelete('cascade');
            $table->string("status");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};

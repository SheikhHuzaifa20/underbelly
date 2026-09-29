<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('address_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('address_id')->nullable();
            $table->integer('user_id')->nullable(); // admin
            $table->json('old_data');
            $table->json('new_data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('address_audit_logs');
    }
};

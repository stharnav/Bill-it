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
        Schema::create('company', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('company_email')->unique();
            $table->string('company_address');
            $table->string('company_motto')->nullable();
            $table->string('company_phone_no');
            $table->string('company_pan')->nullable();
            $table->string('company_registration_no')->nullable();
            $table->string('company_website')->nullable();
            $table->string('currency')->nullable();
            $table->binary('company_logo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company');
    }
};

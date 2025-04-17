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
            $table->foreignId('label_id')->nullable()->constrained('label');
            $table->string('company_legalname')->nullable();
            $table->string('company_brand_name')->nullable();
            $table->string('company_initials')->nullable();
            $table->string('company_parent_company')->nullable();
            $table->string('company_CVR')->nullable();
            $table->string('company_address_1')->nullable();
            $table->string('company_address_2')->nullable();
            $table->string('company_zip_code')->nullable();
            $table->string('company_city')->nullable();
            $table->string('company_state')->nullable();
            $table->string('company_Country')->nullable();
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

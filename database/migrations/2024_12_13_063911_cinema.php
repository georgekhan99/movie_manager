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
       Schema::create('cinemas', function(Blueprint $table){
         $table->id()->autoIncrement();
         $table->string('cinema_name')->nullable();
         $table->string('address_1')->nullable();
         $table->string('address_2')->nullable();
         $table->string('zip')->nullable();
         $table->string('city')->nullable();
         $table->string('state')->nullable();
         $table->string('country')->nullable();
         $table->foreignId('company_id')->constrained('company','id');
         $table->timestamps();
       });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

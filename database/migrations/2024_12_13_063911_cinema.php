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
       Schema::create('cinema', function(Blueprint $table){
         $table->id()->autoIncrement();
         $table->string('cinema_name')->null();
         $table->string('cinema_address')->null();
         $table->string('cinema_contact')->null();
         $table->string('cinema_ditributer')->null();
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

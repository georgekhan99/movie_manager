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
        Schema::create('cinema_placements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cinema_id')->constrained('cinema', 'id');
            $table->string('placement_name')->nullable();;
            $table->string('placement_description')->nullable();;
            $table->string('placement_width')->nullable();;
            $table->string('placement_height')->nullable();
            $table->string('placement_colors')->nullable();
            $table->string('placement_,aterial')->nullable();
            $table->string('placement_price')->nullable();
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('bookings_detail', function (Blueprint $table) {
            $table->dropPrimary();  // Drop the existing primary key
            $table->id();           // Add the new auto-incrementing ID column as the primary key
        });
    }
    
    public function down()
    {
        Schema::table('bookings_detail', function (Blueprint $table) {
            $table->dropColumn('id');      // Drop the newly added ID column
            $table->primary(['booking_id', 'placement_id', 'duration_id']);  // Restore the original composite primary key
        });
    }
};

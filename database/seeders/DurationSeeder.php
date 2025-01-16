<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $start_date = Carbon::create(2025,1, 1);
        $numberOfPeriod = 30;
        for($i = 0; $i < $numberOfPeriod; $i++){
                DB::table('durations')->insert([
                    'start_date' => $start_date,
                    'delivery_date' => $start_date,
                    // 'production_deadline' => $start_date->copy()->subWeek(2),
                    'production_deadline' => $start_date->copy()->subDay(14),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            $start_date->addDays(14);
        }
    }
}

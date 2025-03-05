<?php

namespace Database\Seeders;

use App\Models\Penalty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenaltySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Penalty::factory()->count(15)->create();
    }
}

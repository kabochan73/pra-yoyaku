<?php

namespace Database\Seeders;

use App\Models\Court;
use Illuminate\Database\Seeder;

class CourtSeeder extends Seeder
{
    public function run(): void
    {
        Court::firstOrCreate(
            ['name' => 'Aコート'],
            [
                'description'    => '風情があります。',
                'price_per_hour' => 2000,
            ]
        );
    }
}

<?php

namespace Database\Seeders;

use App\Models\EmdCountry;
use Illuminate\Database\Seeder;

class EmdCountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = [
            [
                'name' => 'United States',
                'code' => 'US',
            ],
            [
                'name' => 'England',
                'code' => 'UK',
            ],
            [
                'name' => 'Canada',
                'code' => 'CA',
            ],
            [
                'name' => 'Germany',
                'code' => 'DE',
            ],
            [
                'name' => 'Russia',
                'code' => 'RU',
            ],
            [
                'name' => 'Netherland',
                'code' => 'NL',
            ],
            [
                'name' => 'India',
                'code' => 'IN',
            ],
            [
                'name' => 'Morocco',
                'code' => 'MA',
            ],
            [
                'name' => 'New Zealand',
                'code' => 'NZ',
            ],

        ];
        foreach ($setting as $key => $value) {
            EmdCountry::firstOrCreate(
                ['code' => $value['code']],
                [
                    'name' => $value['name'],
                ]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\EmdCustomField;
use Illuminate\Database\Seeder;

class EmdCustomFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $custom_fields = [
            [
                'name' => 'Throttle Tool Limit',
                'key' => 'throttle_tool_limit',
                'description' => 'Throttle Tool Limit',
                'default_val' => 20,
                'is_active' => 1,
                'is_all_pages' => 0,
                'is_tool_pages' => 1,
                'is_custom_pages' => 0,
                'tool_id' => 0,
                'emd_custom_page_id' => 0,
            ],
        ];

        foreach ($custom_fields as $key => $value) {
            EmdCustomField::updateOrCreate(
                ['key' => $value['key']],
                [
                    'name' => $value['name'],
                    'description' => $value['description'],
                    'default_val' => $value['default_val'],
                    'is_active' => $value['is_active'],
                    'is_all_pages' => $value['is_all_pages'],
                    'is_tool_pages' => $value['is_tool_pages'],
                    'is_custom_pages' => $value['is_custom_pages'],
                    'tool_id' => $value['tool_id'],
                    'emd_custom_page_id' => $value['emd_custom_page_id'],
                ]
            );
        }
    }
}

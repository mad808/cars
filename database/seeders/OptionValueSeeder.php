<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Value;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OptionValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $options = [
            ['name' => 'Matory', 'sort_order' => 1, 'values' => [
                ['name' => '1.6"', 'sort_order' => 1],
                ['name' => '1.8"', 'sort_order' => 2],
                ['name' => '2.0"', 'sort_order' => 3],
                ['name' => '2.2"', 'sort_order' => 4],
                ['name' => '3.5"', 'sort_order' => 5],
                ['name' => '4.4"', 'sort_order' => 6],
            ]],
            ['name' => 'Kuzow', 'sort_order' => 2, 'values' => [
                ['name' => 'Sedan', 'sort_order' => 1],
                ['name' => 'Krossower', 'sort_order' => 2],
                ['name' => 'Wnedoroznik', 'sort_order' => 3],
                ['name' => 'Miniwen', 'sort_order' => 4],
                ['name' => 'Pikap', 'sort_order' => 5],
                ['name' => 'Furgon', 'sort_order' => 6],
            ]],
            ['name' => 'Yorediji gornus', 'sort_order' => 3, 'values' => [
                ['name' => 'RWD - yzky yorediji', 'sort_order' => 1],
                ['name' => 'FWD - onki yorediji', 'sort_order' => 2],
                ['name' => '4WD - doly yorediji', 'sort_order' => 3],
                ['name' => 'AWD - awtomatiki doly yorediji', 'sort_order' => 4],
            ]],
            ['name' => 'Karobka', 'sort_order' => 4, 'values' => [
                ['name' => 'Mehaniki', 'sort_order' => 1],
                ['name' => 'Awtomatiki', 'sort_order' => 2],
                ['name' => 'Tiptroniki', 'sort_order' => 3],
            ]],
        ];

        foreach ($options as $option) {
            $opt = new Option();
            $opt->name = $option['name'];
            $opt->sort_order = $option['sort_order'];
            $opt->save();

            foreach ($option['values'] as $value) {
                $val = new Value();
                $val->option_id = $opt->id;
                $val->name = $value['name'];
                $val->sort_order = $value['sort_order'];
                $val->save();
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            ['name' => 'Ak', 'name_en' => 'White', 'name_tr' => 'Beyaz', 'name_ru' => 'Белый', 'name_uk' => 'Белый'],
            ['name' => 'Gara', 'name_en' => 'Black', 'name_tr' => 'Siyah', 'name_ru' => 'Черный', 'name_uk' => 'Черный'],
            ['name' => 'Gök', 'name_en' => 'Blue', 'name_tr' => 'Mavi', 'name_ru' => 'Синий', 'name_uk' => 'Синий'],
            ['name' => 'Ýaşyl', 'name_en' => 'Green', 'name_tr' => 'Yeşil', 'name_ru' => 'Зеленый', 'name_uk' => 'Зеленый'],
            ['name' => 'Sary', 'name_en' => 'Yellow', 'name_tr' => 'Sari', 'name_ru' => 'Желтый', 'name_uk' => 'Желтый'],
            ['name' => 'Gyzyl', 'name_en' => 'Red', 'name_tr' => 'Kirmizi', 'name_ru' => 'Красный', 'name_uk' => 'Красный'],
            ['name' => 'Çal', 'name_en' => 'Gray', 'name_tr' => 'Gri', 'name_ru' => 'Серый', 'name_uk' => 'Серый'],
        ];

        foreach ($colors as $color) {
            $obj = new Color();
            $obj->name = $color['name'];
            $obj->name_en = $color['name_en'];
            $obj->name_tr = $color['name_tr'];
            $obj->name_ru = $color['name_ru'];
            $obj->name_uk = $color['name_uk'];
            $obj->save();
        }
    }
}

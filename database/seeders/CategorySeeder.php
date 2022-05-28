<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Ապահովագրական',
                'type' => 'organization',
            ],
            [
                'name' => 'Բարեգործական',
                'type' => 'organization',
            ],
            [
                'name' => 'Անհատ բարեգործ',
                'type' => 'organization',
            ],
            [
                'name' => 'Հոսպիտալ',
                'type' => 'hospital',
            ],
            [
                'name' => 'Վերականգնողական կենտրոն',
                'type' => 'hospital',
            ],
            [
                'name' => 'Առողջարան',
                'type' => 'hospital',
            ],

        ];

        Category::insert($categories);
    }
}

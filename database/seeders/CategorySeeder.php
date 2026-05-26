<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Rock & Metal'],
            ['name' => 'Anime & Manga'],
            ['name' => 'Desporto'],
            ['name' => 'Minimalista'],
            ['name' => 'Natureza'],
            ['name' => 'Tecnologia'],
        ];
        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['code' => 'white', 'name' => 'Branco'],
            ['code' => 'black', 'name' => 'Preto'],
            ['code' => 'red', 'name' => 'Vermelho'],
            ['code' => 'navy', 'name' => 'Azul Marinho'],
            ['code' => 'gray', 'name' => 'Cinzento'],
            ['code' => 'green', 'name' => 'Verde'],
        ];
        foreach ($colors as $color) {
            Color::create($color);
        }
    }
}

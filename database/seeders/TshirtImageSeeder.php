<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TshirtImage;
use App\Models\Category;

class TshirtImageSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('name');

        $images = [
            ['name' => 'Dragon Fire', 'description' => 'Um dragão em chamas em estilo tattoo', 'category' => 'Rock & Metal', 'image_url' => 'tshirt_images/placeholder.png'],
            ['name' => 'Skull Rider', 'description' => 'Caveira com capacete de motard', 'category' => 'Rock & Metal', 'image_url' => 'tshirt_images/placeholder.png'],
            ['name' => 'Naruto Run', 'description' => 'Naruto em corrida icónica', 'category' => 'Anime & Manga', 'image_url' => 'tshirt_images/placeholder.png'],
            ['name' => 'Galaxy Cat', 'description' => 'Gato no espaço com nebulosa', 'category' => 'Natureza', 'image_url' => 'tshirt_images/placeholder.png'],
            ['name' => 'Neon Circuit', 'description' => 'Circuito eletrónico em neon', 'category' => 'Tecnologia', 'image_url' => 'tshirt_images/placeholder.png'],
            ['name' => 'Mountain Peak', 'description' => 'Silhueta de montanha minimalista', 'category' => 'Minimalista', 'image_url' => 'tshirt_images/placeholder.png'],
            ['name' => 'Goal Machine', 'description' => 'Bota de futebol com bola', 'category' => 'Desporto', 'image_url' => 'tshirt_images/placeholder.png'],
            ['name' => 'Pixel Wave', 'description' => 'Onda estilo pixel art', 'category' => 'Tecnologia', 'image_url' => 'tshirt_images/placeholder.png'],
            ['name' => 'Zen Garden', 'description' => 'Jardim zen em traço fino', 'category' => 'Minimalista', 'image_url' => 'tshirt_images/placeholder.png'],
            ['name' => 'Wolf Moon', 'description' => 'Lobo uivando para a lua', 'category' => 'Natureza', 'image_url' => 'tshirt_images/placeholder.png'],
            ['name' => 'Samurai Code', 'description' => 'Samurai com código binário', 'category' => 'Anime & Manga', 'image_url' => 'tshirt_images/placeholder.png'],
            ['name' => 'Thunder Strike', 'description' => 'Raio estilizado em neon', 'category' => 'Rock & Metal', 'image_url' => 'tshirt_images/placeholder.png'],
        ];

        foreach ($images as $img) {
            TshirtImage::create([
                'name' => $img['name'],
                'description' => $img['description'],
                'category_id' => $categories[$img['category']]->id ?? null,
                'image_url' => $img['image_url'],
                'customer_id' => null,
            ]);
        }
    }
}

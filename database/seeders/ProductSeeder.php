<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = collect(['Electrónica', 'Papelería', 'Hogar', 'Deportes', 'Juguetes'])
            ->map(fn ($nombre) => Category::firstOrCreate(['nombre' => $nombre]));

        $bases = ['Laptop', 'Mouse', 'Cuaderno', 'Pelota', 'Lámpara', 'Teclado'];

        for ($i = 1; $i <= 60; $i++) {
            $stock = $i % 7 === 0 ? 0 : random_int(1, 100);

            Product::forceCreate([
                'nombre' => $bases[$i % 6] . " modelo {$i}",
                'descripcion' => "Descripción del producto número {$i}",
                'category_id' => $categorias->random()->id,
                'precio' => random_int(50, 5000) + random_int(0, 99) / 100,
                'existencias' => $stock,
                'estado' => $stock === 0 ? 'agotado' : 'disponible',
                'created_at' => now()->subDays(random_int(0, 365)),
                'updated_at' => now(),
            ]);
        }
    }
}
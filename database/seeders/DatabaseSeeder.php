<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create(['name' => 'Admin', 'email' => 'admin@toko.test', 'password' => Hash::make('password'), 'role' => 'admin']);
        User::factory()->create(['name' => 'Budi', 'email' => 'budi@toko.test', 'password' => Hash::make('password'), 'role' => 'customer']);
        User::factory()->create(['name' => 'Siti', 'email' => 'siti@toko.test', 'password' => Hash::make('password'), 'role' => 'customer']);
        foreach (['Electronics', 'Home', 'Fashion', 'Books'] as $name) {
            $category = Category::create(['name' => $name]);
            for ($index = 1; $index <= 3; $index++) {
                Product::create(['category_id' => $category->id, 'name' => "$name Product $index", 'slug' => Str::slug("$name Product $index"), 'description' => "Quality $name product.", 'price' => $index * 19.99, 'stock' => 20]);
            }
        }
    }
}

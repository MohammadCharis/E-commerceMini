<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EcommerceFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_storefront_displays_products(): void
    {
        $category = Category::create(['name' => 'Test']);
        Product::create(['category_id' => $category->id, 'name' => 'Test Product', 'slug' => 'test-product', 'price' => 10, 'stock' => 2]);
        $this->get('/')->assertOk()->assertSee('Test Product');
    }
}

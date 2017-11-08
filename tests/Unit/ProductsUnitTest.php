<?php

namespace Tests\Unit;

use App\Models\Product;
use Tests\TestCase;

class ProductsUnitTest extends TestCase
{

    /**
     * Test adding product to db
     */
    public function testProductsAddDb()
    {
        // Create a single App\User instance...
        $product = factory(Product::class)->create();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
        ]);

        $product->delete();
    }

}

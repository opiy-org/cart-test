<?php

namespace Tests\Feature;

use App\Models\Product;
use Faker\Factory;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class ApiCartFuncTest extends TestCase
{


    /**
     * @var \Faker\Generator $faker
     */
    protected $faker;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->faker = Factory::create('ru_RU');

        parent::__construct($name, $data, $dataName);
    }


    /**
     * Add product to cart test
     * check if successfully added
     *
     * Add second time - 1 item
     * check if quantity increments
     *
     * Delete from cart 1 item
     * check if quantity decrements
     *
     */
    public function testApiCart()
    {
        //create random product
        $price = $this->faker->numberBetween(10, 1000);
        $name = $this->faker->colorName . ' ' . $this->faker->name('male');
        $descr = $this->faker->realText();

        $product = Product::create([
            'name' => $name,
            'description' => $descr,
            'price' => $price,
        ]);

        //random quantity
        $qnt = $this->faker->numberBetween(1, 10);

        //add first time
        $this->post('/api/cart', [
            'product_id' => $product->id,
            'quantity' => $qnt
        ])
            ->assertStatus(200)
            ->assertJsonMissing([
                'error',
            ]);

        $this->get('/api/cart')
            ->assertJsonFragment([
                'products_count' => $qnt,
                'total_sum' => $qnt * $price
            ])
            ->assertJsonFragment([
                'id' => $product->id,
                'quantity' => $qnt,
                'sum' => $qnt * $price,
            ]);

        //add second time
        $this->post('/api/cart', [
            'product_id' => $product->id,
            'quantity' => 1
        ])->assertStatus(200)
            ->assertJsonMissing([
                'error',
            ]);


        $this->get('/api/cart')
            ->assertJsonFragment([
                'products_count' => $qnt + 1,
                'total_sum' => ($qnt + 1) * $price
            ])
            ->assertJsonFragment([
                'id' => $product->id,
                'quantity' => $qnt + 1,
                'sum' => ($qnt + 1) * $price,
            ]);


        //delete from cart 1 product item
        $this->delete('/api/cart/' . $product->id)
            ->assertStatus(200)
            ->assertJsonMissing([
                'error',
            ]);


        $this->get('/api/cart')
            ->assertJsonFragment([
                'products_count' => $qnt,
                'total_sum' => $qnt * $price
            ])
            ->assertJsonFragment([
                'id' => $product->id,
                'quantity' => $qnt,
                'sum' => $qnt * $price,
            ]);


        //delete product after test
        $product->delete();
    }


}

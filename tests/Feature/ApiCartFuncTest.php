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

    public function testApiCartAdd()
    {
        $qnt = $this->faker->numberBetween(1, 10);
        $price = $this->faker->numberBetween(10, 1000);

        $name = $this->faker->colorName . ' ' . $this->faker->name('male');
        $descr = $this->faker->realText();

        $product = Product::create([
            'name' => $name,
            'description' => $descr,
            'price' => $price,
        ]);


        $response = $this->post('/api/cart', [
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


        $product->delete();
    }


}

<?php

namespace Tests\Feature;

use App\Models\Product;
use Faker\Factory;
use Tests\TestCase;

class ApiCartTest extends TestCase
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
     */
    public function testApiCartAdd()
    {
        //create random product
        $price = $this->faker->numberBetween(10, 1000);
        $product = factory(Product::class)->create(['price' => $price]);

        //random quantity
        $qnt = $this->faker->numberBetween(1, 10);

        //add
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


        //delete product after test
        $product->delete();
    }


    /**
     * Add non-existent product to cart test
     * check got error
     */
    public function testApiCartAddNE()
    {
        //create random product
        $product = factory(Product::class)->create();

        $product->delete();

        //random quantity
        $qnt = $this->faker->numberBetween(1, 10);

        //add
        $this->post('/api/cart', [
            'product_id' => $product->id,
            'quantity' => $qnt
        ])->assertStatus(400)
            ->assertJsonFragment([
                'error',
            ]);
    }


    /**
     * Add product to cart test
     * Add second time - 1 item
     * check if quantity increments
     *
     */
    public function testApiCartAddTwice()
    {
        //create random product
        $price = $this->faker->numberBetween(10, 1000);
        $product = factory(Product::class)->create(['price' => $price]);

        //random quantity
        $qnt = $this->faker->numberBetween(1, 10);

        //add first time
        $this->post('/api/cart', [
            'product_id' => $product->id,
            'quantity' => $qnt
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

        //delete product after test
        $product->delete();
    }

    /**
     * Add product to cart test
     * Add second time - 1 item
     *
     * Delete from cart 1 item
     * check if quantity decrements
     *
     */
    public function testApiCartDecrement()
    {
        //create random product
        $price = $this->faker->numberBetween(10, 1000);
        $product = factory(Product::class)->create(['price' => $price]);

        //random quantity
        $qnt = $this->faker->numberBetween(1, 10);

        //add first time
        $this->post('/api/cart', [
            'product_id' => $product->id,
            'quantity' => $qnt
        ]);

        //add second time
        $this->post('/api/cart', [
            'product_id' => $product->id,
            'quantity' => 1
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


    /**
     * Add 1 item product to cart test
     * Delete from cart 1 item
     * check if cart empty
     *
     */
    public function testApiCartDelete()
    {
        //create random product
        $price = $this->faker->numberBetween(10, 1000);
        $product = factory(Product::class)->create(['price' => $price]);

        //add
        $this->post('/api/cart', [
            'product_id' => $product->id,
            'quantity' => 1
        ]);

        //delete from cart 1 product item
        $this->delete('/api/cart/' . $product->id)
            ->assertStatus(200)
            ->assertJsonMissing([
                'error',
            ]);


        $this->get('/api/cart')
            ->assertJsonFragment([
                'products_count' => 0,
                'total_sum' => 0
            ])
            ->assertJsonMissing([
                'id' => $product->id,
            ]);

        //delete product after test
        $product->delete();
    }


    /**
     * Delete non-existent product from cart
     *
     */
    public function testApiCartDeleteNE()
    {

        //delete from cart 1 product item
        $this->delete('/api/cart/' . rand(100, 300))
            ->assertStatus(400)
            ->assertJsonFragment([
                'error',
            ]);
    }


    /**
     * Delete product from cart, wich not in cart
     *
     */
    public function testApiCartDeleteNotAdded()
    {
        //create random product
        $product = factory(Product::class)->create();


        //delete from cart 1 product item
        $this->delete('/api/cart/' . $product->id)
            ->assertStatus(404)
            ->assertJsonFragment([
                'error',
            ]);
    }

}

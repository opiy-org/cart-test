<?php

namespace Tests\Feature;

use Faker\Factory;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class ApiProductsFuncTest extends TestCase
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
     * Add new product,
     * check if successfully added
     * check if it exist
     *
     * Update it
     * check if successfully changed
     *
     * Delete it
     * check if it not exist
     *
     */
    public function testApiProductsAdd()
    {
        $name = $this->faker->colorName . ' ' . $this->faker->name('male');
        $descr = $this->faker->realText();
        $price = $this->faker->numberBetween(10, 10000);

        //create
        $response = $this->post('/api/products', [
            'name' => $name,
            'description' => $descr,
            'price' => $price,
        ])->assertJsonMissing([
            'error',
        ])->assertJsonFragment([
            'id',
        ]);

        $product = array_get($response->getOriginalContent(), 'data');
        $product_id = object_get($product, 'id', null);


        //check exists
        $this->get('/api/products')
            ->assertJson(['data' => array()])
            ->assertJsonFragment([
                'name' => $name,
                'description' => $descr,
                'price' => $price,
            ]);

        $name_upd = $this->faker->colorName . ' ' . $this->faker->name('male');

        //update
        $this->put('/api/products/' . $product_id, [
            'name' => $name_upd,
            'description' => $descr,
            'price' => $price,
        ])->assertJsonMissing([
            'error',
        ]);

        //check changed
        $this->get('/api/products')
            ->assertJsonFragment([
                'id' => $product_id,
                'name' => $name_upd,
                'description' => $descr,
                'price' => $price,
            ]);


        //delete
        $this->delete('/api/products/' . $product_id)
            ->assertJsonMissing([
                'error',
            ]);

        //check not exists
        $this->get('/api/products')
            ->assertJsonMissing([
                'id' => $product_id,
                'name' => $name_upd,
                'description' => $descr,
                'price' => $price,
            ]);


    }


    /**
     * Add new buggy product,
     * check if not successfully added
     * check if it not exist
     *
     */
    public function testApiProductsAdd400name()
    {
        $name = null;
        $descr = $this->faker->realText();
        $price = $this->faker->numberBetween(10, 10000);

        $response = $this->post('/api/products', [
            'description' => $descr,
            'price' => $price,
        ]);


        $test_resp = new TestResponse($response);

        $test_resp->assertStatus(400);

        $test_resp->assertJsonFragment([
            'error',
        ]);

        $this->get('/api/products')
            ->assertJsonMissing([
                'name' => $name,
                'description' => $descr,
                'price' => $price,
            ]);
    }


    /**
     * Add new buggy product,
     * check if not successfully added
     * check if it not exist
     *
     */
    public function testApiProductsAdd400price()
    {
        $name = $this->faker->colorName . ' ' . $this->faker->name('male');
        $descr = $this->faker->realText();


        $response = $this->post('/api/products', [
            'name' => $name,
            'description' => $descr,
        ]);


        $test_resp = new TestResponse($response);

        $test_resp->assertStatus(400);

        $test_resp->assertJsonFragment([
            'error',
        ]);

        $this->get('/api/products')
            ->assertJsonMissing([
                'name' => $name,
                'description' => $descr,
            ]);
    }


}

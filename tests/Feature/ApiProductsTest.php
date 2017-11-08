<?php

namespace Tests\Feature;

use App\Models\Product;
use Faker\Factory;
use Tests\TestCase;

class ApiProductsTest extends TestCase
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
    public function testApiProducts()
    {
        //random product properties
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

        //get product id from response
        $product = array_get($response->getOriginalContent(), 'data');
        $this->assertTrue(is_object($product));
        $product_id = object_get($product, 'id', null);
        $this->assertTrue($product_id > 0);

        //check exists
        $this->get('/api/products')
            ->assertJson(['data' => array()])
            ->assertJsonFragment([
                'name' => $name,
                'description' => $descr,
                'price' => $price,
            ]);

        //generate new random product name
        $name_upd = $this->faker->colorName . ' ' . $this->faker->name('male');

        //update product (change name)
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
     * Add new product,
     * check if successfully added
     *
     * View it
     *
     * Delete it
     *
     * Try to view
     * check got error
     *
     * Try to update it
     * check got error
     *
     * Try to delete it
     * check got error
     *
     */
    public function testApiProductsAddView()
    {
        $product = factory(Product::class)->create();


        //check exists
        $this->get('/api/products/' . $product->id)
            ->assertJson(['data' => array()])
            ->assertJsonFragment([
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
            ]);

        //delete
        $this->delete('/api/products/' . $product->id)
            ->assertJsonMissing([
                'error',
            ]);


        //can't view non-existent product
        $this->get('/api/products/' . $product->id)
            ->assertJsonFragment(['error'])
            ->assertStatus(404);

        //generate new random product name
        $name_upd = $this->faker->colorName . ' ' . $this->faker->name('male');

        //cat't update  non-existent product
        $this->put('/api/products/' . $product->id, [
            'name' => $name_upd,
            'description' => $product->description,
            'price' => $product->price,
        ])->assertJsonFragment(['error'])
            ->assertStatus(404);


        //can't delete non-existent product
        $this->delete('/api/products/' . $product->id)
            ->assertJsonFragment(['error'])
            ->assertStatus(404);


    }


    /**
     * Add new buggy product,
     * check if not successfully added
     * check if it not exist
     *
     */
    public function testApiProductsAdd400name()
    {
        //random product properties with not valid name
        $name = null;
        $descr = $this->faker->realText();
        $price = $this->faker->numberBetween(10, 10000);

        //try to create product
        //want 400 error
        $this->post('/api/products', [
            'description' => $descr,
            'price' => $price,
        ])
            ->assertStatus(400)
            ->assertJsonFragment([
                'error',
            ]);

        //don't want success response
        $this->get('/api/products')
            ->assertJsonMissing([
                'name' => $name,
                'description' => $descr,
                'price' => $price,
            ]);
    }


    /**
     * Check can't update product with not valid data
     *
     */
    public function testApiProductsUpdate400price()
    {
        $product = factory(Product::class)->create();


        //generate new random product name
        $name_upd = $this->faker->colorName . ' ' . $this->faker->name('male');

        //update product (change name)
        $this->put('/api/products/' . $product->id, [
            'name' => $name_upd,
            'description' => $product->description,
        ])
            ->assertStatus(400)
            ->assertJsonFragment([
                'error',
            ]);

        $product->delete();
    }




}

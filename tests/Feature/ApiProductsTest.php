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
     */
    public function testApiProductsAdd()
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
        ])
            ->assertStatus(200)
            ->assertJsonMissing([
                'error',
            ])->assertJsonFragment([
                'id',
            ]);

        //get product from response
        $product = array_get($response->getOriginalContent(), 'data');
        $this->assertTrue(is_object($product));
        $product->delete();
    }


    /**
     * Find product in index
     */
    public function testApiProductsIndex()
    {
        //random product properties
        $name = $this->faker->colorName . ' ' . $this->faker->name('male');
        $descr = $this->faker->realText();
        $price = $this->faker->numberBetween(10, 10000);

        //create product
        $product = factory(Product::class)->create([
            'name' => $name,
            'description' => $descr,
            'price' => $price,
        ]);

        //check exists
        $this->get('/api/products')
            ->assertStatus(200)
            ->assertJson(['data' => array()])
            ->assertJsonFragment([
                'name' => $name,
                'description' => $descr,
                'price' => $price,
            ]);

        $product->delete();
    }


    /**
     * view product
     */
    public function testApiProductsView()
    {
        //random product properties
        $name = $this->faker->colorName . ' ' . $this->faker->name('male');
        $descr = $this->faker->realText();
        $price = $this->faker->numberBetween(10, 10000);

        //create product
        $product = factory(Product::class)->create([
            'name' => $name,
            'description' => $descr,
            'price' => $price,
        ]);

        //check exists
        $this->get('/api/products/' . $product->id)
            ->assertStatus(200)
            ->assertJson(['data' => array()])
            ->assertJsonFragment([
                'name' => $name,
                'description' => $descr,
                'price' => $price,
            ]);

        $product->delete();
    }


    /**
     * Update product
     * check if successfully changed
     *
     */
    public function testApiProductsUpdate()
    {
        //create product
        $product = factory(Product::class)->create();

        //generate new random product name
        $name = $this->faker->colorName . ' ' . $this->faker->name('female');
        $descr = $this->faker->realText();
        $price = $this->faker->numberBetween(10, 10000);

        //update product (change name)
        $this->put('/api/products/' . $product->id, [
            'name' => $name,
            'description' => $descr,
            'price' => $price,
        ])
            ->assertStatus(200)
            ->assertJsonMissing([
                'error',
            ]);

        //check changed
        $this->get('/api/products')
            ->assertJsonFragment([
                'id' => $product->id,
                'name' => $name,
                'description' => $descr,
                'price' => $price,
            ]);

        $product->delete();
    }


    /**
     * Delete product
     * check if it not exist
     *
     */
    public function testApiProductsDelete()
    {
        //create product
        $product = factory(Product::class)->create();

        //delete
        $this->delete('/api/products/' . $product->id)
            ->assertStatus(200)
            ->assertJsonMissing([
                'error',
            ]);

        //check not exists
        $this->get('/api/products')
            ->assertJsonMissing([
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
            ]);
    }


    /**
     * Try to view non-existent product
     * check got error
     */
    public function testApiProductsViewNE()
    {
        $product = factory(Product::class)->create();
        $product->delete();

        //can't view non-existent product
        $this->get('/api/products/' . $product->id)
            ->assertJsonFragment(['error'])
            ->assertStatus(404);

    }

    /**
     * Try to update non-existent product
     * check got error
     *
     */
    public function testApiProductsUpdateNE()
    {
        $product = factory(Product::class)->create();
        $product->delete();

        //generate new random product name
        $name_upd = $this->faker->colorName . ' ' . $this->faker->name('male');

        //cat't update  non-existent product
        $this->put('/api/products/' . $product->id, [
            'name' => $name_upd,
            'description' => $product->description,
            'price' => $product->price,
        ])->assertJsonFragment(['error'])
            ->assertStatus(404);

    }


    /**
     * Try to delete non-existent product
     * check got error
     *
     */
    public function testApiProductsDeleteNE()
    {
        $product = factory(Product::class)->create();
        $product->delete();


        //can't delete non-existent product
        $this->delete('/api/products/' . $product->id)
            ->assertJsonFragment(['error'])
            ->assertStatus(404);
    }



    /**
     * Add new buggy product,
     * check if not successfully added
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

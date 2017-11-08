<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiRoutesTest extends TestCase
{

    public function testWrongUrl404json()
    {
        $this->get('/' . str_random(rand(8, 20)))
            ->assertJson([
                'error' => array(),
            ]);
    }

    public function testWrongUrl404()
    {
        $response = $this->get('/' . str_random(rand(8, 20)));
        $response->assertStatus(404);
    }


    public function testApiProducts200()
    {
        $response = $this->get('/api/products');
        $response->assertStatus(200);
    }


    public function testApiProductsJson()
    {
        $this->get('/api/products')
            ->assertJson([
                'data' => array(),
            ]);
    }


    public function testApiCart200()
    {
        $response = $this->get('/api/cart');
        $response->assertStatus(200);
    }


    public function testApiCartJson()
    {
        $this->get('/api/cart')
            ->assertJson([
                'data' => array(),
            ]);
    }

}

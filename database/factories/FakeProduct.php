<?php

use Faker\Factory;
use Faker\Generator as Faker;

$factory->define(\App\Models\Product::class, function (Faker $faker) {
    static $price;

    $this->faker = Factory::create('ru_RU');

    $name = $this->faker->colorName . ' ' . $this->faker->name('male');
    $descr = $this->faker->realText();

    return [
        'name' => $name,
        'description' => $descr,
        'price' => $price? $price:$this->faker->numberBetween(10, 1000),
    ];
});
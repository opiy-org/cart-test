<?php

use Faker\Factory;

$factory->define(\App\Models\Product::class, function () {
    static $price;

    $faker = Factory::create('ru_RU');

    $name = $faker->colorName . ' ' . $faker->name('male');
    $descr = $faker->realText();

    return [
        'name' => $name,
        'description' => $descr,
        'price' => $price ? $price : $faker->numberBetween(10, 1000),
    ];
});
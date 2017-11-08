<?php

use Faker\Factory;

$factory->define(\App\Models\Product::class, function () {
    static $price;
    static $name;
    static $description;

    $faker = Factory::create('ru_RU');

    return [
        'name' => $name ? $name : $faker->colorName . ' ' . $faker->name('male'),
        'description' => $description ? $description : $faker->realText(),
        'price' => $price ? $price : $faker->numberBetween(10, 1000),
    ];
});
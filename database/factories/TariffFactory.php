<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tariff;
use Faker\Generator as Faker;

$factory->define(Tariff::class, function (Faker $faker) {
    return [
    	'company_id' => rand(1, 5),
        'name' => $faker->colorName,
        'description' => $faker->text(168),
        'price' => rand(100, 1000),
    ];
});

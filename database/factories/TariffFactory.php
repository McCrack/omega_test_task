<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tariff;
use Faker\Generator as Faker;

$factory->define(Tariff::class, function (Faker $faker) {
    return [
    	'company_id' => rand(1, 15),
        'name' => $faker->jobTitle,
        'description' => $faker->text(168),
        'price' => rand(100, 1000),
    ];
});

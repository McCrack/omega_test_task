<?php

/** @var Factory $factory */

use App\Customer;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'phone' => $faker->tollFreePhoneNumber,
        'is_active' => rand(0, 1),
    ];
});

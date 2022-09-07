<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Customer;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'nama' => $faker->name,
        'no_telp' => $faker->phoneNumber(),
        'email' => $faker->safeEmail(),
        'created_at' => Carbon::now()
    ];
});

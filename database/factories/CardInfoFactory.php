<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CardInfo;
use Faker\Generator as Faker;

$factory->define(CardInfo::class, function (Faker $faker) {
    return [
        'no_kartu' => $faker->creditCardNumber(),
        'exp_date' => $faker->creditCardExpirationDate(),
        'tipe_kartu' => $faker->creditCardType(),
        'nama_pemilik' => $faker->name()
    ];
});

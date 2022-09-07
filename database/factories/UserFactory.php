<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use phpseclib\Crypt\Random;
use Illuminate\Support\Carbon;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'nama' => $faker->name,
        'role_id' => $faker->randomElement(['Owner', 'Operational Manager', 'Cashier', 'Waiter', 'Chef']),
        'telp' => $faker->tollFreePhoneNumber,
        'alamat' => $faker->address,
        'email' => $faker->unique()->freeEmail(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'created_at' => Carbon::now()
    ];
});

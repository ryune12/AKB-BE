<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(CardInfoSeeder::class);
        $this->call(CustomerSeeder::class);
        // $this->call(BahanSeeder::class);
        // $this->call(DetailOrderSeeder::class);
        // $this->call(IncomingStocksSeeder::class);
        // $this->call(MejaSeeder::class);
        // $this->call(MenuSeeder::class);
        // $this->call(OrderSeeder::class);
        // $this->call(RemainingStocksSeeder::class);
        // $this->call(ReservasiSeeder::class);
        // $this->call(WasteStocksSeeder::class);
    }
}

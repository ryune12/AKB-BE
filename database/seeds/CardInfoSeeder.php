<?php

use App\CardInfo;
use Illuminate\Database\Seeder;

class CardInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(CardInfo::class, 50)->create();
    }
}

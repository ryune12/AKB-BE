<?php

namespace App\Console\Commands;

use App\Bahan;
use App\RemainingStocks;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;

class InsertRemaining extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:InsertRemaining';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $bahan = Bahan::all();
        foreach ($bahan as $value) {
            $remaining = new RemainingStocks;
            $remaining->id_bahan = $value->id;
            $remaining->jumlah = $value->jumlah;
            $remaining->created_at = Carbon::now();
            $remaining->save();
        }
        // $remaining->id_bahan = 1;
        // $remaining->jumlah = 2000;
        // $remaining->created_at = Carbon::now();
        // $remaining->save();
    }
}

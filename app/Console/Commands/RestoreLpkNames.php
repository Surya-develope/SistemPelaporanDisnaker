<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RestoreLpkNames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:restore-lpk-names';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = \App\Models\LpkTraining::whereNull('nama_lpk')->orWhere('nama_lpk', '')->update([
            'nama_lpk' => 'LPK Kota Pekanbaru (Data Lama)'
        ]);

        $this->info("Berhasil memulihkan {$count} nama LPK yang kosong.");
    }
}

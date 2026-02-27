<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$loker = App\Models\LowonganKerja::first();
if ($loker) {
    echo "Before: " . $loker->judul_lowongan . "\n";
    $loker->update(['judul_lowongan' => 'Updated Title Test']);
    
    $loker2 = App\Models\LowonganKerja::find($loker->id);
    echo "After: " . $loker2->judul_lowongan . "\n";
} else {
    echo "No data found\n";
}

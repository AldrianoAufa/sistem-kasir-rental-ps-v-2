<?php
/**
 * Test script untuk memverifikasi fix Lost Time timer at midnight
 * Jalankan dengan: php artisan tinker < test_midnight_timer.php
 * Atau: php test_midnight_timer.php (dari root folder)
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Transaction;
use App\Models\Device;
use Carbon\Carbon;

echo "=== TEST: Lost Time Timer at Midnight Fix ===\n\n";

$today = Carbon::now()->toDateString();
$yesterday = Carbon::yesterday()->toDateString();

// Test 1: Cek apakah ada transaksi postpaid yang sedang berjalan
echo "1. Mencari transaksi postpaid yang sedang berjalan...\n";
$runningPostpaid = Transaction::where('tipe_transaksi', 'postpaid')
    ->where('status_transaksi', 'berjalan')
    ->get();

if ($runningPostpaid->count() > 0) {
    echo "   ✅ Ditemukan " . $runningPostpaid->count() . " transaksi postpaid berjalan:\n";
    foreach ($runningPostpaid as $t) {
        echo "      - ID: {$t->id_transaksi}, Nama: {$t->nama}, ";
        echo "Dibuat: {$t->created_at}, Lost Time Start: {$t->lost_time_start}\n";
    }
} else {
    echo "   ℹ️  Tidak ada transaksi postpaid yang sedang berjalan\n";
}

echo "\n";

// Test 2: Simulasi query baru yang menyertakan transaksi dari hari sebelumnya
echo "2. Testing query yang sudah diperbaiki (include previous day running transactions)...\n";

$devices = Device::where('status', 'Digunakan')->get();

foreach ($devices as $device) {
    // Query yang sudah diperbaiki
    $transaction = Transaction::where('device_id', $device->id)
        ->where(function ($query) use ($today) {
            $query->whereDate('created_at', $today)
                ->orWhere(function ($q) {
                    $q->where('tipe_transaksi', 'postpaid')
                        ->where('status_transaksi', 'berjalan');
                });
        })
        ->latest()
        ->first();

    if ($transaction) {
        $createdDate = $transaction->created_at->toDateString();
        $isFromPreviousDay = $createdDate !== $today;
        
        echo "   Device: {$device->nama}\n";
        echo "   - Transaksi ID: {$transaction->id_transaksi}\n";
        echo "   - Tipe: {$transaction->tipe_transaksi}\n";
        echo "   - Status: {$transaction->status_transaksi}\n";
        echo "   - Dibuat: {$transaction->created_at}\n";
        
        if ($isFromPreviousDay && $transaction->tipe_transaksi === 'postpaid') {
            echo "   ✅ Transaksi dari hari sebelumnya BERHASIL ditemukan!\n";
        }
        echo "\n";
    }
}

// Test 3: Verifikasi format lost_time_start
echo "3. Verifikasi format lost_time_start...\n";
$postpaidWithLostTime = Transaction::where('tipe_transaksi', 'postpaid')
    ->whereNotNull('lost_time_start')
    ->latest()
    ->first();

if ($postpaidWithLostTime) {
    echo "   - Lost Time Start (raw): {$postpaidWithLostTime->lost_time_start}\n";
    $parsed = Carbon::parse($postpaidWithLostTime->lost_time_start);
    echo "   - Parsed: {$parsed->toIso8601String()}\n";
    echo "   ✅ Format dapat di-parse dengan benar\n";
} else {
    echo "   ℹ️  Tidak ada transaksi postpaid dengan lost_time_start\n";
}

echo "\n=== TEST SELESAI ===\n";

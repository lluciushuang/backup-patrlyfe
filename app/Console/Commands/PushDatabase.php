<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PushDatabase extends Command
{
    protected $signature = 'sync:push-cloud';
    protected $description = 'Kirim data segar dari lokal untuk menimpa/memperbarui database Cloud cPanel';

    public function handle()
    {
        $this->warn('PERINGATAN: Perintah ini akan mengirim data lokal dan memperbarui data di Cloud!');
        if (!$this->confirm('Apakah kamu yakin mau melanjutkan?', false)) {
            return 0;
        }

        $token = env('SYNC_TOKEN', 'PartLyfeSyncSecure999');
        
        // Susunan urutan yang aman dari bentrokan Foreign Key:
        $tables = [
            'customer_tiers',
            'categories',
            'products',
            'users',
            'customer_tier_assignments',
            'product_prices',
            'product_price_tiers',
            'product_images',
            'stock_movements',
            'carts',
            'wishlists',
            'transactions',
            'transaction_details',
            'pos_transactions',
            'broadcasts',
            'sessions',
            'cache',
            'cache_locks',
            'jobs',
            'job_batches',
            'failed_jobs',
            'migrations',
            'password_reset_tokens'
        ];

        foreach ($tables as $table) {
            $this->info("Mengirim data lokal untuk tabel: {$table}...");

            try {
                // Ambil semua data yang ada di database laptopmu saat ini
                $localData = DB::table($table)->get();
                $dataArray = json_decode(json_encode($localData), true);

                if (empty($dataArray)) {
                    $this->warn("-> Lewat: Tabel {$table} di lokal kosongan.");
                    continue;
                }

                // 🚀 Alur Naik: Lokal langsung menembak rute bebas hambatan di cPanel
                $url = 'https://sirheyyou.my.id/sync/receive';

                $response = Http::withoutVerifying()
                    ->withHeaders(['X-Sync-Token' => $token])
                    ->post($url, [
                        'table' => $table,
                        'data'  => $dataArray
                    ]);

                if ($response->successful()) {
                    $this->info("-> BERHASIL: Tabel {$table} sukses diunggah dan disinkronkan ke Cloud.");
                } else {
                    $this->error("-> GAGAL pada tabel {$table} (Status: " . $response->status() . ")");
                    $this->error("Pesan Cloud: " . $response->body());
                    return 0;
                }

            } catch (\Exception $e) {
                $this->error("-> ERROR SISTEM: " . $e->getMessage());
                return 0;
            }
        }

        $this->info('Semua data dari database lokal sukses di-push ke Cloud cPanel!');
        return 0;
    }
}
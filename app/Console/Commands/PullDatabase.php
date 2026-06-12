<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PullDatabase extends Command
{
    protected $signature = 'sync:pull-reset';
    protected $description = 'Ambil data segar dari Cloud untuk menimpa database lokal yang berantakan';

    public function handle()
    {
        $this->warn('PERINGATAN: Perintah ini akan mengosongkan tabel lokal dan mengisinya dengan data dari Cloud!');
        if (!$this->confirm('Apakah kamu yakin mau melanjutkan?', false)) {
            return 0;
        }

        $token = env('SYNC_TOKEN', 'PartLyfeSyncSecure999');
        // TIMPA ARRAY $tables LAMA DENGAN DAFTAR LENGKAP 23 TABEL INI:
        $tables = [
            'users',
            'customer_tiers',
            'customer_tier_assignments',
            'categories',
            'products',
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
            $this->info("Mengambil data untuk tabel: {$table}...");

            try {
                // 🚀 SOLUSI PASTI: Langsung tembak ke rute web luar grup admin di cPanel
                $url = 'https://sirheyyou.my.id/sync/pull';
                
                $response = Http::withoutVerifying()
                    ->withHeaders(['X-Sync-Token' => $token])
                    ->get($url, ['table' => $table]);

                // Proteksi ketat: Jika server cloud mengembalikan error (seperti 404), langsung hentikan!
                if ($response->failed()) {
                    $this->error("-> GAGAL pada tabel {$table} (Status: " . $response->status() . ")");
                    $this->error("Pesan Error: Silakan cek apakah route di cPanel sudah benar-benar berada di luar grup admin.");
                    return 0; // Stop command biar tidak lancang memunculkan teks sukses gadungan
                }

                $data = $response->json('data');

                if (is_array($data)) {
                    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                    DB::table($table)->truncate(); // Bersihkan database lokal baru
                    
                    foreach ($data as $row) {
                        DB::table($table)->insert($row);
                    }
                    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
                    
                    $this->info("-> BERHASIL: Tabel {$table} di lokal sekarang sama dengan Cloud.");
                } else {
                    $this->error("-> GAGAL: Format data dari cloud bukan array yang valid.");
                    return 0;
                }

            } catch (\Exception $e) {
                $this->error("-> ERROR SISTEM: " . $e->getMessage());
                return 0;
            }
        }

        $this->info('Database lokal sukses di-reset mengikuti Cloud!');
        return 0;
    }
}
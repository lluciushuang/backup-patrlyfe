<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SyncDatabase extends Command
{
    // Ini perintah yang akan dipanggil oleh Windows Task Scheduler nanti
    protected $signature = 'sync:run {--dry-run}';

    protected $description = 'Sinkronisasi data dari database lokal ke database cloud di cPanel';

    public function handle()
    {
        // 1. Cek Role dari .env, kalau bukan local maka stop (biar ga tabrakan di cloud)
        if (config('app.env') !== 'local' && env('SYNC_ROLE', 'local') !== 'local') {
            $this->error('Perintah ini hanya boleh berjalan di lingkungan lokal!');
            return 0;
        }

        $peerUrl = env('SYNC_PEER_URL');
        $token = env('SYNC_TOKEN', 'PartLyfeSyncSecure999'); // <--- GANTI dengan token pilihanmu

        if (!$peerUrl) {
            $this->error('SYNC_PEER_URL belum diatur di file .env lokal!');
            return 0;
        }

        $isDryRun = $this->option('dry-run');
        if ($isDryRun) {
            $this->info('=== RUNNING IN DRY-RUN MODE (Hanya Tes Koneksi) ===');
        }

        // 2. Daftar tabel yang mau kamu sinkronkan (bisa ditambah sesuai kebutuhan mata kuliah)
        // Urutannya diperhatikan ya, tabel induk harus duluan sebelum tabel anak (Foreign Key)
        $tablesToSync = [
            'categories',
            'products',
            'product_prices',
            'product_images',
        ];

        $this->info('Memulai sinkronisasi data ke cPanel...');

        foreach ($tablesToSync as $table) {
            $this->info("Memeriksa tabel: {$table}");

            // Ambil data yang belum disinkronkan (is_synced = 0)
            // Catatan: Jika tabelmu belum punya kolom is_synced, command ini akan mengambil semua data
            $query = DB::table($table);
            
            if (\Schema::hasColumn($table, 'is_synced')) {
                $query->where('is_synced', 0);
            }

            $rows = $query->get();

            if ($rows->isEmpty()) {
                $this->line("-> Tabel {$table} sudah sinkron (tidak ada data baru).");
                continue;
            }

            $this->info("-> Menemukan " . $rows->count() . " data baru di tabel {$table}. Mengirim...");

            if ($isDryRun) {
                $this->info("-> [Dry-Run] Simulasi kirim data sukses (data tidak benar-benar dikirim).");
                continue;
            }

            // Konversi data collection menjadi array biasa
            $dataArray = json_decode(json_encode($rows), true);

            // 3. Tembak API Cloud cPanel menggunakan HTTP Client Laravel
            try {
                $response = Http::withoutVerifying()
                    ->withHeaders(['X-Sync-Token' => $token])
                    ->post($peerUrl . '/sync/receive', [
                        'table' => $table,
                        'data'  => $dataArray
                    ]);

                if ($response->successful()) {
                    $this->info("-> BERHASIL: Tabel {$table} sukses disinkronkan ke cloud.");

                    // Jika tabel memiliki kolom is_synced, update menjadi 1 agar tidak dikirim lagi menit berikutnya
                    if (\Schema::hasColumn($table, 'is_synced')) {
                        DB::table($table)
                            ->whereIn('id', $rows->pluck('id'))
                            ->update(['is_synced' => 1]);
                    }
                } else {
                    $this->error("-> GAGAL: Server merespons dengan status " . $response->status() . " - " . $response->body());
                }
            } catch (\Exception $e) {
                $this->error("-> ERROR JARINGAN: Gagal terhubung ke cloud. Pesan: " . $e->getMessage());
            }
        }

        $this->info('Proses sinkronisasi selesai!');
        return 0;
    }
}
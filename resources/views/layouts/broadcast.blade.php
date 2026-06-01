@extends('layouts.admin')
@section('title', 'Broadcast Notifikasi')

@push('styles')
<style>
    .form-group { margin-bottom: 1.25rem; }
    .form-group label { display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; }
    .form-control { width: 100%; padding: 0.75rem; background: var(--surface-2); border: 1px solid var(--border); color: var(--text-main); border-radius: 4px; box-sizing: border-box; }
</style>
@endpush

@section('content')
    <h2>Pusat Komunikasi Pelanggan</h2>
    <div style="background:var(--surface); padding:2rem; border:1px solid var(--border); border-radius:4px; max-width:600px; margin-top:1.5rem;">
        <form onsubmit="event.preventDefault(); alert('Siaran Notifikasi Berhasil Disemburkan ke Semua Customer!');">
            <div class="form-group">
                <label>Judul Broadcast</label>
                <input type="text" class="form-control" placeholder="Contoh: Promo Ganti Oli Akhir Tahun!" required>
            </div>
            <div class="form-group">
                <label>Isi Pesan Promosi</label>
                <textarea class="form-control" rows="5" placeholder="Tulis penawaran menarikmu di sini..." required></textarea>
            </div>
            <div class="form-group">
                <label>Tipe Siaran</label>
                <select class="form-control" style="appearance: none;"><option>🎫 Promosi / Diskon</option><option>⚙️ Info Sistem / Restock</option></select>
            </div>
            <button type="submit" class="btn" style="width: 100%; padding: 1rem; font-size: 1rem;">Kirim ke Semua Pelanggan</button>
        </form>
    </div>
@endsection
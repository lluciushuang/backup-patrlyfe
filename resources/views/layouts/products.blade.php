@extends('layouts.admin')
@section('title', 'Data Produk')

@push('styles')
<style>
    .table { width: 100%; border-collapse: collapse; background: var(--surface); border-radius: 4px; overflow: hidden; }
    .table th, .table td { padding: 1rem; text-align: left; border-bottom: 1px solid var(--border); }
    .table th { color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; background: #151513; }
</style>
@endpush

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
        <h2>Manajemen Katalog Produk</h2>
        <button class="btn" onclick="adminToast.fire({ icon: 'info', title: 'Pop Up Modal Tambah Produk (Hardcoded)' })">+ Tambah Produk</button>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Kode Part</th>
                <th>Nama Produk</th>
                <th>Merek</th>
                <th>Sisa Stok</th>
                <th>Harga Retail</th>
                <th>Aksi CRUD</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-family:monospace; color:var(--orange);">PRT-001</td>
                <td>Kampas Rem Depan Vario</td>
                <td>AHM</td>
                <td>50 Pcs</td>
                <td>Rp 55.000</td>
                <td>
                    <button class="btn" style="padding:0.35rem 0.7rem; font-size:0.8rem; background:transparent; border:1px solid var(--orange); color:var(--orange);">Edit</button>
                    <button class="btn" style="padding:0.35rem 0.7rem; font-size:0.8rem; background:transparent; border:1px solid red; color:red;">Hapus</button>
                </td>
            </tr>
            <tr>
                <td style="font-family:monospace; color:var(--orange);">PRT-002</td>
                <td>Oli Mesin MPX 2</td>
                <td>AHM</td>
                <td>120 Pcs</td>
                <td>Rp 54.000</td>
                <td>
                    <button class="btn" style="padding:0.35rem 0.7rem; font-size:0.8rem; background:transparent; border:1px solid var(--orange); color:var(--orange);">Edit</button>
                    <button class="btn" style="padding:0.35rem 0.7rem; font-size:0.8rem; background:transparent; border:1px solid red; color:red;">Hapus</button>
                </td>
            </tr>
        </tbody>
    </table>
@endsection
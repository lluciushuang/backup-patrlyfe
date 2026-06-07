@extends('layouts.admin')

@section('title', 'Tambah Stok - POS')

@section('content')
<div class="admin-page-header">
    <h2 class="admin-page-title">Tambah Stok Produk</h2>
</div>

<div class="admin-card">
    <h3 class="admin-card-title">Form Tambah Stok</h3>
    <form id="stockForm">
        <div class="form-group">
            <label for="product_id">Produk</label>
            <select name="product_id" id="product_id" class="form-control" required>
                <option value="">Pilih Produk</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }} (Stok: {{ $product->current_stock }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="quantity">Jumlah</label>
            <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
        </div>
        <div class="form-group">
            <label for="supplier">Supplier</label>
            <input type="text" name="supplier" id="supplier" class="form-control">
        </div>
        <div class="form-group">
            <label for="notes">Catatan</label>
            <textarea name="notes" id="notes" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn">Simpan</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('stockForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('{{ route('admin.pos.stock-in.store') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        }).then(r => r.json()).then(data => {
            if (data.success) {
                adminToast.fire({icon: 'success', title: data.message});
                this.reset();
            } else {
                adminToast.fire({icon: 'error', title: data.message});
            }
        });
    });
</script>
@endpush
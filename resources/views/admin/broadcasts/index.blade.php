@extends('layouts.admin')
@section('title', 'Kirim Broadcast')

@push('styles')
<style>
    .broadcast-form { max-width: 600px; }
    .customer-select { max-height: 200px; overflow-y: auto; border: 1px solid var(--border); border-radius: 4px; padding: 0.5rem; background: var(--surface); }
    .customer-option { padding: 0.5rem; cursor: pointer; }
    .customer-option:hover { background: rgba(232, 82, 26, 0.1); }
    .customer-option input { margin-right: 0.5rem; }
</style>
@endpush

@section('content')
    <div class="admin-page-header">
        <h2 class="admin-page-title">Kirim Broadcast</h2>
    </div>

    <div class="admin-card broadcast-form">
        <h3 class="admin-card-title">Detail Pesan Broadcast</h3>
        
        <form id="broadcastForm" method="POST" action="{{ route('admin.broadcasts.send') }}">
            @csrf

            <div class="form-group">
                <label>Judul *</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Isi Pesan *</label>
                <textarea name="message" id="message" class="form-control" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label>Tipe *</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="promo">Promo</option>
                    <option value="system">Sistem</option>
                    <option value="info">Info</option>
                </select>
            </div>

            <div class="form-group">
                <label>Target *</label>
                <select name="target_type" id="target_type" class="form-control" required onchange="toggleTargetOptions()">
                    <option value="all">Semua Customer</option>
                    <option value="specific_tier">Berdasarkan Level Harga</option>
                    <option value="specific_user">Customer Tertentu</option>
                </select>
            </div>

            <div id="tier_target" class="form-group" style="display: none;">
                <label>Pilih Level Harga</label>
                <div class="customer-select">
                    @foreach(\App\Models\CustomerTier::all() as $tier)
                    <label class="customer-option">
                        <input type="checkbox" name="tier_ids[]" value="{{ $tier->id }}"> {{ $tier->name }} ({{ $tier->discount_percent }}% diskon)
                    </label>
                    @endforeach
                </div>
            </div>

            <div id="user_target" class="form-group" style="display: none;">
                <label>Cari Customer</label>
                <input type="text" id="user_search" class="form-control" placeholder="Ketik nama atau email customer..." onkeyup="searchUsers()">
                <div id="user_results" class="customer-select" style="margin-top: 0.5rem; display: none;"></div>
                <div id="selected_users" style="margin-top: 0.5rem;"></div>
            </div>

            <button type="submit" class="btn" style="width: 100%;">Kirim Broadcast Sekarang</button>
        </form>
    </div>
@endsection

@push('scripts')
<script>
function toggleTargetOptions() {
    const targetType = document.getElementById('target_type').value;
    document.getElementById('tier_target').style.display = targetType === 'specific_tier' ? 'block' : 'none';
    document.getElementById('user_target').style.display = targetType === 'specific_user' ? 'block' : 'none';
}

function searchUsers() {
    const query = document.getElementById('user_search').value;
    if (query.length < 2) {
        document.getElementById('user_results').style.display = 'none';
        return;
    }
    
    fetch('{{ route('admin.users.search') }}?q=' + encodeURIComponent(query) + '&_t=' + Date.now(), {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(r => r.json())
    .then(data => {
        let html = '';
        if (data.users && data.users.length > 0) {
            data.users.forEach(user => {
                html += '<label class="customer-option"><input type="checkbox" name="user_ids[]" value="' + user.id + '"> ' + user.name + ' (' + user.email + ')</label>';
            });
        } else {
            html = '<div style="padding:0.5rem; color:var(--text-muted);">Tidak ada pengguna ditemukan</div>';
        }
        document.getElementById('user_results').innerHTML = html;
        document.getElementById('user_results').style.display = 'block';
    })
    .catch(err => {
        console.error(err);
        document.getElementById('user_results').innerHTML = '<div style="padding:0.5rem; color:var(--red);">Error mencari pengguna</div>';
        document.getElementById('user_results').style.display = 'block';
    });
}

document.getElementById('broadcastForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            adminToast.fire({ icon: 'success', title: data.message || 'Berhasil' });
            document.getElementById('broadcastForm').reset();
            toggleTargetOptions();
        } else {
            adminToast.fire({ icon: 'error', title: data.message || 'Terjadi kesalahan' });
        }
    })
    .catch(err => {
        console.error(err);
        adminToast.fire({ icon: 'error', title: 'Terjadi kesalahan jaringan' });
    });
});
</script>
@endpush
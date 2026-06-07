@extends('layouts.admin')
@section('title', 'Level Harga Customer')

@push('styles')
<style>
    .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; }
    .modal.active { display: flex; align-items: center; justify-content: center; }
    .modal-content { background: var(--surface-2); padding: 2rem; border-radius: 8px; width: 90%; max-width: 500px; }
</style>
@endpush

@section('content')
    <div class="admin-page-header">
        <h2 class="admin-page-title">Level Harga Customer</h2>
        <button class="btn" onclick="openTierModal()">+ Tambah Level</button>
    </div>

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Level</th>
                    <th>Price Level</th>
                    <th>Min Pembelian</th>
                    <th>Pengguna</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tiers as $tier)
                <tr>
                    <td>{{ $loop->iteration + ($tiers->currentPage() - 1) * $tiers->perPage() }}</td>
                    <td>{{ $tier->name }}</td>
                    <td><span style="background:#E8521A; color:white; padding:0.25rem 0.75rem; border-radius:4px; font-weight:600;">{{ $tier->name }}</span></td>
                    <td>Rp {{ number_format($tier->min_purchase, 0, ',', '.') }}</td>
                    <td>{{ $tier->assignments_count }} pengguna</td>
                    <td>
                        <button class="btn btn-sm" style="background:transparent; border:1px solid var(--orange); color:var(--orange);" onclick="editTier({{ $tier->id }})">Edit</button>
                        <button class="btn btn-sm" style="background:transparent; border:1px solid #dc3545; color:#dc3545; margin-left:0.5rem;" onclick="deleteTier({{ $tier->id }})">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; color:var(--text-muted);">Belum ada level harga</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($tiers->hasPages())
    <div style="margin-top:1.5rem;">
        {{ $tiers->links('vendor.pagination.default') }}
    </div>
    @endif

    <div id="tierModal" class="modal">
        <div class="modal-content">
            <div class="modal-header-custom">
                <h3 id="modalTitle">Tambah Level Harga</h3>
            </div>
            <form id="tierForm" method="POST">
                @csrf
                <input type="hidden" id="tierId" name="tierId">
                <div class="form-group">
                    <label>Nama Level *</label>
                    <input type="text" name="name" id="tier_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Price Level * (1 = Eceran, 4 = Grosir Besar)</label>
                    <select name="price_level" id="price_level" class="form-control" required>
                        <option value="">Pilih Level Harga</option>
                        <option value="1">Level 1 - Eceran</option>
                        <option value="2">Level 2 - Grosir Kecil</option>
                        <option value="3">Level 3 - Grosir Menengah</option>
                        <option value="4">Level 4 - Grosir Besar</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Min Pembelian (Rp) *</label>
                    <input type="number" name="min_purchase" id="min_purchase" class="form-control" min="0" value="0" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                </div>
                <div style="display:flex; gap:1rem; margin-top:1.5rem; border-top: 1px solid rgba(242, 239, 230, 0.08); padding-top: 1rem;">
                    <button type="submit" class="btn">Simpan</button>
                    <button type="button" class="btn" style="background:transparent; border:1px solid var(--border);" onclick="closeTierModal()">Batal</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function openTierModal() {
    document.getElementById('tierModal').classList.add('active');
    document.getElementById('modalTitle').textContent = 'Tambah Level Harga';
    document.getElementById('tierForm').reset();
    document.getElementById('tierId').value = '';
    document.getElementById('tierForm').action = '{{ route('admin.tiers.store') }}';
}

function closeTierModal() {
    document.getElementById('tierModal').classList.remove('active');
}

function editTier(tierId) {
    fetch('{{ route('admin.tiers.edit', ':id') }}'.replace(':id', tierId))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tier = data.tier;
                document.getElementById('tierModal').classList.add('active');
                document.getElementById('modalTitle').textContent = 'Edit Level Harga';
                document.getElementById('tierId').value = tier.id;
                document.getElementById('tier_name').value = tier.name || '';
                document.getElementById('price_level').value = tier.price_level || 1;
                document.getElementById('min_purchase').value = tier.min_purchase || 0;
                document.getElementById('description').value = tier.description || '';
                document.getElementById('tierForm').action = '{{ route('admin.tiers.update', ':id') }}'.replace(':id', tierId);
            }
        })
        .catch(err => {
            console.error('Error:', err);
            adminToast.fire({ icon: 'error', title: 'Gagal mengambil data level harga' });
        });
}

document.getElementById('tierForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const tierId = document.getElementById('tierId').value;
    const url = tierId ? 
        '{{ route('admin.tiers.update', ':id') }}'.replace(':id', tierId) : 
        '{{ route('admin.tiers.store') }}';

    const formData = new FormData(this);
    if (tierId) {
        formData.append('_method', 'PUT');
    }

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            adminToast.fire({ icon: 'error', title: data.message || 'Terjadi kesalahan' });
        }
    })
    .catch(err => {
        console.error('Error:', err);
        adminToast.fire({ icon: 'error', title: 'Terjadi kesalahan jaringan' });
    });
});

function deleteTier(tierId) {
    if (!confirm('Yakin ingin menghapus level harga ini?')) return;
    
    fetch('{{ route('admin.tiers.destroy', ':id') }}'.replace(':id', tierId), {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            adminToast.fire({ icon: 'error', title: data.message || 'Gagal menghapus level harga' });
        }
    })
    .catch(err => {
        console.error('Error:', err);
        adminToast.fire({ icon: 'error', title: 'Terjadi kesalahan jaringan' });
    });
}
</script>
@endpush
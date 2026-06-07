@extends('layouts.admin')
@section('title', 'Manajemen Pengguna')

@push('styles')
<style>
    .tier-badge { padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; background: rgba(232, 82, 26, 0.2); color: var(--orange); }
</style>
@endpush

@section('content')
    <div class="admin-page-header">
        <h2 class="admin-page-title">Manajemen Pengguna</h2>
    </div>

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Level Harga Aktif</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone ?? '-' }}</td>
                    <td>
                        @php $activeTier = $user->activeTier @endphp
                        @if($activeTier)
                            <span class="tier-badge">{{ $activeTier->name }}</span>
                        @else
                            <span style="color: var(--text-muted);">Default</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-sm" style="background:transparent; border:1px solid var(--orange); color:var(--orange);" onclick="assignTier({{ $user->id }}, '{{ $user->name }}')">Ubah Level</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; color:var(--text-muted);">Belum ada pengguna</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div style="margin-top:1.5rem;">
        {{ $users->links() }}
    </div>
    @endif

    <div id="tierModal" class="modal">
        <div class="modal-content">
            <div class="modal-header-custom">
                <h3>Ubah Level Harga untuk <span id="userName"></span></h3>
            </div>
            <form id="tierForm" method="POST">
                @csrf
                <input type="hidden" id="tierUserId" name="user_id">
                <div class="form-group">
                    <label>Pilih Level Harga *</label>
                    <select name="tier_id" id="tier_id" class="form-control" required>
                        <option value="">Pilih Level</option>
                        @foreach($tiers as $tier)
                        <option value="{{ $tier->id }}">{{ $tier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Masa Berlaku (Opsional)</label>
                    <input type="date" name="expires_at" id="expires_at" class="form-control" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    <small style="color:var(--text-muted); display:block; margin-top:0.25rem;">Kosongkan untuk tier permanen</small>
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
function assignTier(userId, userName) {
    document.getElementById('tierModal').classList.add('active');
    document.getElementById('userName').textContent = userName;
    document.getElementById('tierUserId').value = userId;
    document.getElementById('tierForm').action = '{{ route('admin.users.assign-tier', ':id') }}'.replace(':id', userId);
}

function closeTierModal() {
    document.getElementById('tierModal').classList.remove('active');
}

document.getElementById('tierForm').addEventListener('submit', function(e) {
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
</script>
@endpush
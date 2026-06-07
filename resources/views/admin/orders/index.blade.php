@extends('layouts.admin')
@section('title', 'Manajemen Pesanan')

@push('styles')
<style>
    .order-status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 600;
    }
    .status-pending { background: rgba(255,193,7,0.2); color: #ffc107; }
    .status-processing { background: rgba(0,123,255,0.2); color: #007bff; }
    .status-shipped { background: rgba(40,167,69,0.2); color: #28a745; }
    .status-delivered { background: rgba(46,204,113,0.2); color: var(--green); }
    .status-done { background: rgba(108,117,125,0.2); color: #6c757d; }
    .status-cancelled { background: rgba(220,53,69,0.2); color: #dc3545; }
</style>
@endpush

@section('content')
    <div class="admin-page-header">
        <h2 class="admin-page-title">Manajemen Pesanan</h2>
    </div>

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>{{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                    <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td>
                        <span class="order-status-badge status-{{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; color:var(--text-muted);">Belum ada pesanan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
    <div style="margin-top:1.5rem;">
        {{ $orders->links() }}
    </div>
    @endif
@endsection
{{-- resources/views/profile.blade.php ── RE-DESIGN SENADA PREMIUM PARTLYFE --}}
@extends('layouts.app')
@section('title', 'Account Settings')

@push('styles')
<style>
    /* ── PROFILE INDUSTRIAL GRID LAYOUT ── */
    .account-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 2.5rem;
        align-items: flex-start;
        margin-top: 1rem;
    }

    /* ── SIDEBAR SLICK CYBER BLOCKS ── */
    .account-sidebar {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .account-card {
        background: linear-gradient(145deg, #121210 0%, #0d0d0b 100%);
        border: 1px solid rgba(242, 239, 230, 0.05);
        border-radius: 4px;
        overflow: hidden;
        padding: 1.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    .profile-avatar-wrap {
        position: relative;
        width: 80px;
        height: 80px;
        margin: 0 auto 1rem;
    }
    .profile-avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: #181815;
        border: 2px solid var(--orange);
        box-shadow: 0 0 15px rgba(232, 82, 26, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: var(--font-display);
        font-size: 1.8rem;
        color: var(--orange);
        letter-spacing: 0.05em;
    }
    .avatar-verified-badge {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 22px;
        height: 22px;
        background: var(--orange);
        border: 2px solid #0d0d0b;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 10px;
    }
    .profile-meta-name {
        font-family: var(--font-display);
        font-size: 1.5rem;
        letter-spacing: 0.03em;
        text-align: center;
        color: var(--off-white);
        line-height: 1.2;
    }
    .profile-meta-sub {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        color: var(--gray-mid);
        text-align: center;
        margin-top: 0.25rem;
        letter-spacing: 0.02em;
    }

    /* Sidebar Navigation Menu */
    .account-nav {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        margin-top: 1.5rem;
    }
    .anav-link {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 0.75rem 1rem;
        color: var(--gray-light);
        text-decoration: none;
        font-size: 0.88rem;
        font-weight: 500;
        border-radius: 3px;
        transition: all 0.2s ease;
        border-left: 2px solid transparent;
    }
    .anav-link svg {
        width: 18px;
        height: 18px;
        opacity: 0.6;
        transition: opacity 0.2s;
    }
    .anav-link:hover {
        background: rgba(242, 239, 230, 0.03);
        color: var(--off-white);
    }
    .anav-link:hover svg {
        opacity: 1;
    }
    .anav-link.active {
        background: rgba(232, 82, 26, 0.07);
        color: var(--orange);
        font-weight: 600;
        border-left-color: var(--orange);
    }
    .anav-link.active svg {
        opacity: 1;
        color: var(--orange);
    }
    .anav-link.logout-btn {
        color: var(--red);
        margin-top: 1rem;
        border-top: 1px solid rgba(242, 239, 230, 0.05);
        padding-top: 1rem;
        border-radius: 0;
    }
    .anav-link.logout-btn:hover {
        background: rgba(231, 76, 60, 0.05);
        color: var(--red);
    }

    /* ── MAIN CONTENT WORKBENCH ── */
    .account-main {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    .main-section-title {
        font-family: var(--font-display);
        font-size: 2rem;
        letter-spacing: 0.04em;
        color: var(--off-white);
        margin-bottom: 0.5rem;
    }

    /* Dashboard UI Grid Blocks */
    .profile-dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    @media (max-width: 1100px) {
        .profile-dashboard-grid { grid-template-columns: 1fr; }
    }

    /* Container Card Base */
    .profile-form-card {
        background: #111110;
        border: 1px solid rgba(242, 239, 230, 0.05);
        border-radius: 4px;
        padding: 1.75rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }
    .profile-form-card.full-width {
        grid-column: 1 / -1;
    }
    .pfc-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(242, 239, 230, 0.06);
        padding-bottom: 0.85rem;
        margin-bottom: 1.5rem;
    }
    .pfc-title {
        font-family: var(--font-display);
        font-size: 1.3rem;
        letter-spacing: 0.05em;
        color: var(--off-white);
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }
    .pfc-title svg {
        width: 18px;
        height: 18px;
        color: var(--orange);
    }

    /* Progress Tracker Bar */
    .progress-pill-box {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(46, 204, 113, 0.08);
        border: 1px solid rgba(46, 204, 113, 0.2);
        padding: 0.25rem 0.6rem;
        border-radius: 2px;
        font-family: var(--font-mono);
        font-size: 0.68rem;
        color: var(--green);
        font-weight: 700;
    }

    /* ── FORMS AND INPUT CONTROLS ── */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
    }
    .form-group.full-span {
        grid-column: 1 / -1;
    }
    .form-label {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--gray-mid);
    }
    .input-icon-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }
    .input-icon-wrapper svg {
        position: absolute;
        left: 1rem;
        width: 16px;
        height: 16px;
        color: var(--gray-mid);
        pointer-events: none;
    }
    .form-control {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        background: #161614;
        border: 1px solid rgba(242, 239, 230, 0.08);
        border-radius: 3px;
        color: var(--off-white);
        font-family: var(--font-body);
        font-size: 0.88rem;
        outline: none;
        transition: all 0.2s ease;
    }
    .form-control:focus {
        border-color: var(--orange);
        background: #1a1a17;
        box-shadow: 0 0 10px rgba(232, 82, 26, 0.1);
    }
    .form-control:disabled {
        background: #0d0d0b;
        color: var(--gray-mid);
        border-color: rgba(242, 239, 230, 0.03);
        cursor: not-allowed;
    }
    
    /* Small Badge Inside Input Area */
    .input-inner-badge {
        position: absolute;
        right: 1rem;
        font-family: var(--font-mono);
        font-size: 0.65rem;
        padding: 0.15rem 0.4rem;
        border-radius: 2px;
        pointer-events: none;
    }
    .input-inner-badge.success { background: rgba(46,204,113,0.15); color: var(--green); }
    .input-inner-badge.warning { background: rgba(232,82,26,0.15); color: var(--orange); }

    /* ── TOGGLE LIST (PREFERENSI) ── */
    .notif-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .notif-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.85rem 1rem;
        background: #151513;
        border: 1px solid rgba(242, 239, 230, 0.03);
        border-radius: 3px;
    }
    .notif-info {
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
    }
    .notif-title {
        font-size: 0.88rem;
        font-weight: 500;
        color: var(--off-white);
    }
    .notif-sub {
        font-size: 0.78rem;
        color: var(--gray-mid);
    }

    /* Elegant Custom Cyber Toggle Switch */
    .cyber-switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 22px;
        flex-shrink: 0;
    }
    .cyber-switch input { opacity: 0; width: 0; height: 0; }
    .switch-slider {
        position: absolute; cursor: pointer; inset: 0;
        background-color: #262622;
        border: 1px solid rgba(242,239,230,0.1);
        border-radius: 34px;
        transition: .25s ease;
    }
    .switch-slider::before {
        position: absolute; content: "";
        height: 14px; width: 14px; left: 3px; bottom: 3px;
        background-color: var(--gray-light);
        border-radius: 50%;
        transition: .25s ease;
    }
    .cyber-switch input:checked + .switch-slider {
        background-color: rgba(232, 82, 26, 0.2);
        border-color: var(--orange);
    }
    .cyber-switch input:checked + .switch-slider::before {
        transform: translateX(22px);
        background-color: var(--orange);
        box-shadow: 0 0 8px var(--orange);
    }

    /* ── INDUSTRIAL MAP ELEMENT (ALAMAT) ── */
    .map-mockup-wrapper {
        border: 1px solid rgba(242, 239, 230, 0.06);
        background: #141412;
        border-radius: 3px;
        overflow: hidden;
        margin-top: 0.5rem;
    }
    .map-canvas-area {
        height: 135px;
        background-color: #0c0c0b;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    /* Vector Map Line Grid Graphics Overlay */
    .map-grid-blueprint {
        position: absolute; inset: 0; opacity: 0.08;
        background-image: 
            linear-gradient(rgba(242,239,230,0.5) 1px, transparent 1px),
            linear-gradient(90deg, rgba(242,239,230,0.5) 1px, transparent 1px);
        background-size: 20px 20px;
    }
    .map-road-mock {
        position: absolute; width: 150%; height: 10px; background: rgba(242,239,230,0.05); transform: rotate(-25deg);
    }
    .map-road-mock.cross {
        width: 10%; height: 150%; left: 40%; transform: rotate(15deg);
    }
    .map-pin-center {
        position: relative; z-index: 2; color: var(--orange);
        animation: pulsePin 2s infinite alternate;
    }
    @keyframes pulsePin {
        0% { transform: scale(1); filter: drop-shadow(0 0 2px var(--orange)); }
        100% { transform: scale(1.15); filter: drop-shadow(0 0 10px var(--orange)); }
    }
    .map-address-details {
        padding: 1.25rem;
        background: #111110;
        border-top: 1px solid rgba(242,239,230,0.05);
    }

    /* Action Footer Rows */
    .form-save-row {
        display: flex;
        justify-content: flex-end;
        margin-top: 1.5rem;
        padding-top: 1.2rem;
        border-top: 1px solid rgba(242, 239, 230, 0.05);
    }
</style>
@endpush

@section('content')
<div class="page-wrapper"> {{-- Pembungkus Pengaman Tengah Layar --}}
    <div class="breadcrumb">
        <a href="{{ route('home') }}">HOME</a> <span>/</span> <a href="#">ACCOUNT</a> <span>/</span> PROFILE
    </div>

<div class="account-layout">
    
    {{-- SIDEBAR CONTAINER BLOCK ── PROFILE CARD & ACC NAVIGATION --}}
    <aside class="account-sidebar">
        <div class="account-card">
            <div class="profile-avatar-wrap">
                <div class="profile-avatar">RH</div>
                <div class="avatar-verified-badge" title="Verified Account">✓</div>
            </div>
            <h2 class="profile-meta-name">Rizal Hermawan</h2>
            <div class="profile-meta-sub">ID: PL-98240-2026</div>

            <nav class="account-nav">
                <a href="#" class="anav-link active">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Profile Settings
                </a>
                <a href="#" class="anav-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    My Orders
                </a>
                <a href="#" class="anav-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    My Wishlist
                </a>
                <a href="#" class="anav-link logout-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Log Out
                </a>
            </nav>
        </div>
    </aside>

    {{-- MAIN INTERFACE CORES ── SUBSECTIONS & PREFERENCES GRID --}}
    <main class="account-main">
        <div>
            <h1 class="main-section-title">ACCOUNT SETTINGS</h1>
        </div>

        <div class="profile-dashboard-grid">
            
            {{-- BLOCK 1: CONTACT INFORMATIONS --}}
            <div class="profile-form-card">
                <div class="pfc-header">
                    <div class="pfc-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        Informasi Kontak
                    </div>
                    <div class="progress-pill-box" title="Kelengkapan data profil kamu">
                        Profile Complete 95%
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group full-span">
                        <label class="form-label">Nama Lengkap</label>
                        <div class="input-icon-wrapper">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <input type="text" class="form-control" value="Rizal Hermawan" placeholder="Masukkan nama lengkap">
                        </div>
                    </div>

                    <div class="form-group full-span">
                        <label class="form-label">Nomor Telepon</label>
                        <div class="input-icon-wrapper">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <input type="text" class="form-control" value="+62 812-3456-7890" placeholder="Masukkan nomor HP">
                            <span class="input-inner-badge success">Verified</span>
                        </div>
                    </div>

                    <div class="form-group full-span">
                        <label class="form-label">Alamat Email</label>
                        <div class="input-icon-wrapper">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <input type="email" class="form-control" value="rizal.h@email.com" placeholder="Alamat Email" disabled>
                            <span class="input-inner-badge success">Primary</span>
                        </div>
                    </div>
                </div>

                <div class="form-save-row">
                    <button class="btn btn-primary btn-sm">Simpan Perubahan</button>
                </div>
            </div>

            {{-- BLOCK 2: SHIPPING ADDRESS ELEMENTS --}}
            <div class="profile-form-card">
                <div class="pfc-header">
                    <div class="pfc-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        Alamat Pengiriman Utama
                    </div>
                </div>

                <div class="map-mockup-wrapper">
                    <div class="map-canvas-area">
                        <div class="map-grid-blueprint"></div>
                        <div class="map-road-mock"></div>
                        <div class="map-road-mock cross"></div>
                        <div class="map-pin-center">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        </div>
                    </div>
                    <div class="map-address-details">
                        <div style="font-weight: 600; font-size: 0.95rem; color: var(--off-white); margin-bottom: 0.2rem;">Rumah Utama</div>
                        <p style="font-size: 0.82rem; color: var(--gray-light); line-height: 1.5;">
                            Jl. Rungkut Industri No. 45, Kecamatan Rungkut, Kota Surabaya, Jawa Timur — 60293
                        </p>
                    </div>
                </div>

                <div class="form-save-row" style="margin-top: 1.8rem;">
                    <button class="btn btn-outline btn-sm">Ubah Alamat</button>
                </div>
            </div>

            {{-- BLOCK 3: NOTIFICATION PREFERENCES (FULL WIDTH) --}}
            <div class="profile-form-card full-width">
                <div class="pfc-header">
                    <div class="pfc-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                        Preferensi Notifikasi Sistem
                    </div>
                </div>

                <div class="notif-list">
                    @foreach([
                        ['Status Pengiriman Pesanan', 'Kirimkan update live tracking sparepart via email & push notif.', true],
                        ['Informasi Flash Sale & Promo', 'Dapatkan info diskon part mesin, ban, dan oli up-to-date.', true],
                        ['Notifikasi Restok Barang', 'Beri tahu saya jika barang di Wishlist sudah ready stock kembali.', false]
                    ] as [$title, $description, $status])
                    <div class="notif-row">
                        <div class="notif-info">
                            <div class="notif-title">{{ $title }}</div>
                            <div class="notif-sub">{{ $description }}</div>
                        </div>
                        <label class="cyber-switch">
                            <input type="checkbox" {{ $status ? 'checked' : '' }}>
                            <span claqss="switch-slider"></span>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </main>
</div>
</div>
@endsection
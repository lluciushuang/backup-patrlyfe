@extends('layouts.app')

@section('title', 'Partlyfe — Home')

@section('content')
    {{-- HERO --}}
    <section class="hero" style="width: 100vw; position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw; display: grid; grid-template-columns: 1fr 1fr;">
        <div class="hero-bg-text">MOTOR</div>

        <div class="hero-left" style="padding: 6rem 3rem 6rem 8rem;">
            <span class="hero-eyebrow">Toko Sparepart Motor #1 Indonesia</span>
            <h1 class="hero-title" style="font-size: clamp(4rem, 6.5vw, 7.5rem); line-height: 0.92; font-family: var(--font-display); color: var(--off-white);">
                SPAREPART<br>
                MOTOR<br>
                <span class="accent">TERBAIK.</span>
            </h1>
            <p class="hero-sub">
                Dari mesin hingga bodi — semua tersedia. Suku cadang original & aftermarket berkualitas untuk semua merek motor favoritmu.
            </p>
            <div class="hero-actions">
                <a href="{{ route('produk.index') }}" class="btn btn-primary btn-lg" style="padding: 1rem 2.5rem; font-size: 1rem; border-radius: 2px;">
                    Belanja Sekarang
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; margin-left: 0.5rem;">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="{{ route('kategori.index') }}" class="hero-link" style="margin-left: 1.5rem;">Lihat Kategori</a>
            </div>
            <div class="hero-stats">
                <div>
                    <div class="hero-stat-num">{{ number_format(\App\Models\Product::count()) }}+</div>
                    <div class="hero-stat-label">Produk Tersedia</div>
                </div>
                <div>
                    <div class="hero-stat-num">200K+</div>
                    <div class="hero-stat-label">Pelanggan Puas</div>
                </div>
                <div>
                    <div class="hero-stat-num">99%</div>
                    <div class="hero-stat-label">Produk Original</div>
                </div>
            </div>
        </div>

        <div class="hero-right">
            <div class="hero-image-block">
                <svg class="hero-engine-svg" viewBox="0 0 800 900" xmlns="http://www.w3.org/2000/svg" style="width:100%;height:100%;position:absolute;inset:0;object-fit:cover;">
                    <defs>
                        <radialGradient id="glow" cx="50%" cy="50%" r="50%">
                            <stop offset="0%" stop-color="#E8521A" stop-opacity="0.15"/>
                            <stop offset="100%" stop-color="#0A0A08" stop-opacity="0"/>
                        </radialGradient>
                    </defs>
                    <rect width="800" height="900" fill="#0F0F0D"/>
                    <ellipse cx="400" cy="450" rx="350" ry="350" fill="url(#glow)"/>
                    <g transform="translate(160, 180)" opacity="0.6">
                        <rect x="140" y="80" width="200" height="260" rx="8" fill="none" stroke="#E8521A" stroke-width="1.5"/>
                        <rect x="160" y="100" width="160" height="40" rx="4" fill="none" stroke="#C8C4B8" stroke-width="1" opacity="0.5"/>
                        <rect x="160" y="150" width="160" height="40" rx="4" fill="none" stroke="#C8C4B8" stroke-width="1" opacity="0.5"/>
                        <rect x="160" y="200" width="160" height="40" rx="4" fill="none" stroke="#C8C4B8" stroke-width="1" opacity="0.5"/>
                        <rect x="160" y="250" width="160" height="40" rx="4" fill="none" stroke="#C8C4B8" stroke-width="1" opacity="0.5"/>
                        <rect x="120" y="40" width="240" height="50" rx="6" fill="none" stroke="#E8521A" stroke-width="2"/>
                        <circle cx="180" cy="65" r="12" fill="none" stroke="#888880" stroke-width="1"/>
                        <circle cx="240" cy="65" r="12" fill="none" stroke="#888880" stroke-width="1"/>
                        <circle cx="300" cy="65" r="12" fill="none" stroke="#888880" stroke-width="1"/>
                        <path d="M340 200 Q420 200 420 280 Q420 360 340 360" fill="none" stroke="#E8521A" stroke-width="2" stroke-dasharray="6,4" opacity="0.7"/>
                        <rect x="140" y="340" width="200" height="60" rx="6" fill="none" stroke="#888880" stroke-width="1" opacity="0.5"/>
                        <path d="M140 160 Q60 160 60 240 Q60 300 140 300" fill="none" stroke="#C8C4B8" stroke-width="1.5" opacity="0.4"/>
                        <circle cx="240" cy="460" r="70" fill="none" stroke="#E8521A" stroke-width="2" opacity="0.5"/>
                        <circle cx="240" cy="460" r="40" fill="none" stroke="#888880" stroke-width="1" opacity="0.4"/>
                        <line x1="240" y1="390" x2="240" y2="340" stroke="#C8C4B8" stroke-width="1.5" opacity="0.5"/>
                        <circle cx="148" cy="88" r="5" fill="#1A1A16" stroke="#888880" stroke-width="1" opacity="0.6"/>
                        <circle cx="332" cy="88" r="5" fill="#1A1A16" stroke="#888880" stroke-width="1" opacity="0.6"/>
                        <circle cx="148" cy="332" r="5" fill="#1A1A16" stroke="#888880" stroke-width="1" opacity="0.6"/>
                        <circle cx="332" cy="332" r="5" fill="#1A1A16" stroke="#888880" stroke-width="1" opacity="0.6"/>
                    </g>
                </svg>
                <div class="hero-badge">Original &amp; Bergaransi</div>
                <div class="hero-ticker">
                    <div class="ticker-label">Merek Tersedia</div>
                    <div class="ticker-items">
                        <span class="ticker-item">Honda</span>
                        <span class="ticker-item">Yamaha</span>
                        <span class="ticker-item">Suzuki</span>
                        <span class="ticker-item">Kawasaki</span>
                        <span class="ticker-item">TVS</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- MARQUEE --}}
    <div class="marquee-wrap" style="width: 100vw; position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw;">
        <div class="marquee-track">
            @foreach(range(1, 2) as $i)
                <span>ORIGINAL PARTS</span><span class="dot">✦</span>
                <span>FAST DELIVERY</span><span class="dot">✦</span>
                <span>GARANSI RESMI</span><span class="dot">✦</span>
                <span>50.000+ PRODUK</span><span class="dot">✦</span>
                <span>HARGA TERBAIK</span><span class="dot">✦</span>
                <span>SPAREPART MOTOR</span><span class="dot">✦</span>
                <span>PARTLYFE</span><span class="dot">✦</span>
            @endforeach
        </div>
    </div>

    {{-- KATEGORI --}}
    <section class="kategori section-home" style="width: 100vw; position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw; padding: 6rem 8rem;">
        <div class="kategori-header">
            <div>
                <span class="sec-label">Jelajahi</span>
                <h2 class="sec-title" style="color: var(--off-white);">KATEGORI PRODUK</h2>
            </div>
            <a href="{{ route('kategori.index') }}" class="btn btn-outline btn-sm">Lihat Semua →</a>
        </div>

<div class="kategori-grid">
             @foreach($categories as $kat)
             <a href="{{ route('kategori.show', str()->slug($kat->name)) }}" class="kategori-card">
                <div class="kategori-icon-wrap">
                    @if(str_contains(strtolower($kat->name), 'mesin'))
                    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <rect x="20" y="25" width="60" height="50" rx="4" fill="currentColor"/>
                        <rect x="35" y="10" width="30" height="20" rx="3" fill="currentColor"/>
                        <rect x="10" y="40" width="15" height="20" rx="2" fill="currentColor"/>
                        <rect x="75" y="40" width="15" height="20" rx="2" fill="currentColor"/>
                        <circle cx="50" cy="80" r="12" fill="currentColor"/>
                    </svg>
                    @elif(str_contains(strtolower($kat->name), 'rem') || str_contains(strtolower($kat->name), 'pengereman'))
                    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="50" cy="50" r="40" fill="currentColor"/>
                        <circle cx="50" cy="50" r="25" fill="#141410"/>
                        <circle cx="50" cy="50" r="10" fill="currentColor"/>
                        <rect x="46" y="5" width="8" height="20" rx="2" fill="currentColor"/>
                        <rect x="46" y="75" width="8" height="20" rx="2" fill="currentColor"/>
                        <rect x="5" y="46" width="20" height="8" rx="2" fill="currentColor"/>
                        <rect x="75" y="46" width="20" height="8" rx="2" fill="currentColor"/>
                    </svg>
                    @elif(str_contains(strtolower($kat->name), 'listrik'))
                    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <polygon points="55,5 30,55 50,55 45,95 70,45 50,45" fill="currentColor"/>
                    </svg>
                    @else
                    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <rect x="10" y="30" width="80" height="40" rx="6" fill="currentColor"/>
                        <rect x="20" y="20" width="60" height="15" rx="3" fill="currentColor"/>
                        <circle cx="25" cy="78" r="8" fill="currentColor"/>
                        <circle cx="75" cy="78" r="8" fill="currentColor"/>
                    </svg>
                    @endif
                </div>
                <div class="kategori-info">
                    <div class="kategori-name" style="color: var(--off-white);">{{ $kat->name }}</div>
                    <div class="kategori-count">{{ $kat->products_count }} produk</div>
                </div>
                <div class="kategori-arrow">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7 17l10-10M7 7h10v10"/>
                    </svg>
                </div>
            </a>
            @endforeach
        </div>
    </section>

    {{-- PRODUK FEATURED --}}
    <section class="produk-featured section-home" style="width: 100vw; position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw; padding: 6rem 8rem;">
        <div class="produk-header">
            <div>
                <span class="sec-label">Terlaris Minggu Ini</span>
                <h2 class="sec-title" style="color: var(--off-white);">PRODUK UNGGULAN</h2>
            </div>
            <a href="{{ route('produk.index') }}" class="btn btn-outline btn-sm">Lihat Semua →</a>
        </div>

<div class="produk-grid">
             @foreach($featuredProducts as $product)
             <a href="{{ route('produk.show', $product->id) }}" class="prod-card">
                <div class="produk-brand">{{ $product->brand }}</div>
                <div class="produk-img-wrap">
                    @if($loop->first)
                        <div class="produk-badge">Terlaris</div>
                    @elseif($loop->iteration == 3)
                        <div class="produk-badge">New</div>
                    @endif
                    
                    @if($product->images->count() > 0)
                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: contain;">
                    @else
                        <svg width="120" height="120" viewBox="0 0 100 100" class="produk-img-placeholder" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="50" cy="50" r="42" fill="none" stroke="#E8E3D6" stroke-width="1.5"/>
                            <circle cx="50" cy="50" r="28" fill="none" stroke="#E8E3D6" stroke-width="1"/>
                            <circle cx="50" cy="50" r="10" fill="#E8E3D6"/>
                        </svg>
                    @endif
                </div>
                <div class="produk-name" style="color: var(--off-white);">{{ $product->name }}</div>
                <div class="produk-spec">Stok: {{ $product->current_stock }} {{ $product->unit }}</div>
                <div class="produk-footer">
                    <div>
                        @php $price = $product->prices->where('price_level', 1)->first(); @endphp
                        @if($product->base_price > ($price->price ?? 0))
                            <div class="produk-price-old">Rp {{ number_format($product->base_price, 0, ',', '.') }}</div>
                        @endif
                        <div class="produk-price">Rp {{ number_format($price->price ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <button class="produk-add" onclick="event.preventDefault()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                    </button>
                </div>
            </a>
            @endforeach
        </div>
    </section>

    {{-- PROMO BANNER --}}
    <div class="promo-section" style="width: 100vw; position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw; padding: 0 8rem 4rem;">
        <div class="promo-banner">
            <div>
                <div class="promo-eyebrow">Penawaran Terbatas · Berakhir Minggu Ini</div>
                <h2 class="promo-title">GANTI OLI<br>HEMAT 30%<br>BULAN INI</h2>
                <p class="promo-sub">Oli mesin, filter, dan semua produk perawatan berkurang harga. Jaga performa motormu tanpa perlu khawatir dengan harga.</p>
            </div>
            <a href="{{ route('produk.index') }}" class="btn-promo">
                Klaim Promo Sekarang →
            </a>
        </div>
    </div>
@endsection
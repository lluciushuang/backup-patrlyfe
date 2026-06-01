<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Partlyfe') — Sparepart Motor</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --black: #0A0A08;
            --off-white: #F2EFE6;
            --cream: #E8E3D6;
            --surface: #111110;
            --surface-2: #181816;
            --orange: #E8521A;
            --orange-dark: #C03F0E;
            --orange-light: #FF6B35;
            --gray-mid: #888880;
            --gray-light: #C8C4B8;
            --green: #2ECC71;
            --red: #E74C3C;
            --font-display: 'Bebas Neue', sans-serif;
            --font-body: 'DM Sans', sans-serif;
            --font-mono: 'Space Mono', monospace;
        }
        html { scroll-behavior: smooth; }
        body {
            background: var(--black);
            color: var(--off-white);
            font-family: var(--font-body);
            font-size: 15px;
            line-height: 1.6;
            min-height: 100vh;
        }
        
        a { color: inherit; text-decoration: none; }
        button { font-family: inherit; }

        /* ── NAV RESPONSIVE ── */
        .nav {
            position: sticky; top: 0; z-index: 200;
            height: 64px;
            background: rgba(10,10,8,0.92);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(242,239,230,0.07);
            display: flex; align-items: center;
            padding: 0 4rem; gap: 1.5rem;
        }
        .nav-logo {
            font-family: var(--font-display); font-size: 1.7rem;
            letter-spacing: 0.04em; color: var(--off-white);
            flex-shrink: 0;
        }
        .nav-logo span { color: var(--orange); }
        
        .nav-search {
            flex: 1; max-width: 440px;
            display: flex; align-items: center;
            background: var(--surface); border: 1px solid rgba(242,239,230,0.1);
            border-radius: 3px; overflow: hidden;
        }
        .nav-search input {
            flex: 1; padding: 0.55rem 1rem;
            background: transparent; border: none; color: var(--off-white);
            font-family: var(--font-body); font-size: 0.85rem; outline: none;
        }
        .nav-search input::placeholder { color: var(--gray-mid); }
        .nav-search-btn {
            padding: 0.55rem 1rem; background: var(--orange);
            border: none; color: var(--off-white); cursor: pointer;
            display: flex; align-items: center;
        }
        .nav-search-btn svg { width: 16px; height: 16px; }
        .nav-spacer { flex: 1; }
        .nav-icons { display: flex; align-items: center; gap: 0.25rem; }
        .nav-icon-btn {
            width: 40px; height: 40px; border-radius: 3px;
            background: transparent; border: none; color: var(--gray-light);
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            position: relative; transition: all 0.2s;
        }
        .nav-icon-btn:hover { background: var(--surface); color: var(--off-white); }
        .nav-icon-btn svg { width: 20px; height: 20px; }
        .nav-badge {
            position: absolute; top: 4px; right: 4px;
            width: 16px; height: 16px; border-radius: 50%;
            background: var(--orange); color: var(--off-white);
            font-size: 9px; font-weight: 700; font-family: var(--font-mono);
            display: flex; align-items: center; justify-content: center;
        }
        .nav-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: #2A2A26; border: 1.5px solid rgba(232,82,26,0.4);
            display: flex; align-items: center; justify-content: center;
            font-family: var(--font-mono); font-size: 0.65rem; font-weight: 700;
            color: var(--orange); cursor: pointer; flex-shrink: 0; transition: border-color 0.2s;
        }

        /* ── PAGE WRAPPER UTK SUB-UI (SELAIN HOME) ── */
        .page-wrapper { max-width: 1320px; margin: 0 auto; padding: 2rem 4rem 4rem; }

        /* ── BREADCRUMB ── */
        .breadcrumb {
            display: flex; align-items: center; gap: 0.4rem;
            font-size: 0.75rem; color: var(--gray-mid); margin-bottom: 2rem;
            font-family: var(--font-mono); letter-spacing: 0.04em;
            flex-wrap: wrap;
        }
        .breadcrumb span { color: var(--orange); }

        /* ── BUTTONS ── */
        .btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.65rem 1.5rem; border-radius: 2px; font-family: var(--font-body); font-size: 0.87rem; font-weight: 600; letter-spacing: 0.04em; cursor: pointer; border: none; transition: all 0.2s; }
        .btn-primary { background: var(--orange); color: var(--off-white); }
        .btn-primary:hover { background: var(--orange-light); }
        .btn-outline { background: transparent; color: var(--off-white); border: 1px solid rgba(242,239,230,0.2); }
        .btn-outline:hover { border-color: var(--off-white); background: rgba(242,239,230,0.05); }
        .btn-sm { padding: 0.45rem 1rem; font-size: 0.78rem; }
        .btn-lg { padding: 0.9rem 2.5rem; font-size: 0.95rem; }
        
        /* ── TAGS / BADGES ── */
        .badge { display: inline-flex; align-items: center; padding: 0.2rem 0.6rem; border-radius: 2px; font-family: var(--font-mono); font-size: 0.62rem; letter-spacing: 0.08em; text-transform: uppercase; font-weight: 700; }
        .badge-orange { background: var(--orange); color: var(--off-white); }
        .badge-green { background: rgba(46,204,113,0.15); color: var(--green); border: 1px solid rgba(46,204,113,0.2); }
        
        /* ── SECTION TITLES ── */
        .sec-label { display: inline-flex; align-items: center; gap: 0.5rem; font-family: var(--font-mono); font-size: 0.65rem; letter-spacing: 0.12em; text-transform: uppercase; color: var(--orange); margin-bottom: 0.75rem; }
        .sec-label::before { content: ''; width: 16px; height: 1px; background: var(--orange); }
        .sec-title { font-family: var(--font-display); font-size: 2.2rem; letter-spacing: 0.03em; line-height: 1; margin-bottom: 2rem; }

        /* ── DROPDOWN NOTIFIKASI ── */
        .nav-icon-wrapper { position: relative; display: inline-block; }
        .notif-dropdown { position: absolute; top: 120%; right: 0; width: 340px; background: linear-gradient(145deg, #121210 0%, #0d0d0b 100%); border: 1px solid rgba(242, 239, 230, 0.08); border-radius: 4px; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6); display: none; z-index: 250; overflow: hidden; }
        .notif-dropdown.show { display: block; animation: dropFade 0.2s ease-out; }
        @keyframes dropFade { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
        .notif-drop-header { padding: 1rem; border-bottom: 1px solid rgba(242, 239, 230, 0.06); font-family: var(--font-display); font-size: 1.1rem; letter-spacing: 0.05em; color: var(--off-white); display: flex; justify-content: space-between; align-items: center; }
        .notif-drop-list { max-height: 280px; overflow-y: auto; }
        .notif-drop-item { padding: 1rem; border-bottom: 1px solid rgba(242, 239, 230, 0.03); display: flex; gap: 0.85rem; cursor: pointer; transition: background 0.15s; position: relative; }
        .notif-drop-item.unread { background: rgba(232, 82, 26, 0.02); }
        .notif-drop-icon { width: 32px; height: 32px; background: #161614; border-radius: 3px; display: flex; align-items: center; justify-content: center; color: var(--orange); flex-shrink: 0; }
        .notif-drop-icon.success { color: var(--green); }
        .notif-drop-body { flex: 1; }
        .notif-drop-title { font-size: 0.85rem; font-weight: 500; color: var(--off-white); line-height: 1.3; margin-bottom: 0.15rem; }
        .notif-drop-time { font-family: var(--font-mono); font-size: 0.68rem; color: var(--gray-mid); }
        .unread-dot { width: 6px; height: 6px; background: var(--orange); border-radius: 50%; position: absolute; top: 1.25rem; right: 1rem; box-shadow: 0 0 8px var(--orange); }

        .footer-mini { border-top: 1px solid rgba(242,239,230,0.06); padding: 2rem; text-align: center; font-size: 0.75rem; color: var(--gray-mid); font-family: var(--font-mono); letter-spacing: 0.06em; }

        /* ── CSS SPESIFIK LANDING PAGE (HOME) ── */
        .hero { min-height: 100vh; display: grid; grid-template-columns: 1fr 1fr; position: relative; overflow: hidden; }
        .hero-bg-text { position: absolute; bottom: -2rem; left: -1rem; font-family: var(--font-display); font-size: clamp(8rem, 18vw, 22rem); color: rgba(232,82,26,0.04); letter-spacing: -0.02em; user-select: none; pointer-events: none; white-space: nowrap; }
        .hero-left { display: flex; flex-direction: column; justify-content: center; padding: 6rem 3rem 6rem 4rem; position: relative; z-index: 1; }
        .hero-eyebrow { display: inline-flex; align-items: center; gap: 0.6rem; font-family: var(--font-mono); font-size: 0.72rem; letter-spacing: 0.12em; text-transform: uppercase; color: var(--orange); margin-bottom: 1.5rem; }
        .hero-eyebrow::before { content: ''; display: block; width: 24px; height: 1px; background: var(--orange); }
        .hero-title { font-family: var(--font-display); font-size: clamp(4.5rem, 7vw, 8rem); line-height: 0.92; letter-spacing: 0.01em; margin-bottom: 2rem; }
        .hero-title .accent { color: var(--orange); }
        .hero-sub { font-size: 1.05rem; line-height: 1.7; color: var(--gray-light); max-width: 380px; margin-bottom: 3rem; }
        .hero-actions { display: flex; gap: 1rem; align-items: center; }
        .hero-link { color: var(--gray-light); text-decoration: none; font-size: 0.9rem; font-weight: 500; border-bottom: 1px solid rgba(200,196,184,0.3); padding-bottom: 2px; transition: all 0.2s; }
        .hero-link:hover { color: var(--off-white); border-color: var(--off-white); }
        .hero-stats { display: flex; gap: 3rem; margin-top: 4rem; padding-top: 2.5rem; border-top: 1px solid rgba(242,239,230,0.1); }
        .hero-stat-num { font-family: var(--font-display); font-size: 2.2rem; color: var(--off-white); letter-spacing: 0.02em; }
        .hero-stat-label { font-size: 0.75rem; color: var(--gray-mid); letter-spacing: 0.06em; text-transform: uppercase; margin-top: 0.2rem; }
        .hero-right { position: relative; display: flex; align-items: stretch; overflow: hidden; }
        .hero-image-block { width: 100%; background: #141410; position: relative; overflow: hidden; }
        .hero-image-block::after { content: ''; position: absolute; inset: 0; background: linear-gradient(120deg, rgba(10,10,8,0.4) 0%, transparent 60%); }
        .hero-engine-svg { width: 100%; height: 100%; object-fit: cover; opacity: 0.7; }
        .hero-badge { position: absolute; top: 2.5rem; left: -1rem; background: var(--orange); color: var(--off-white); padding: 0.6rem 1.2rem 0.6rem 2rem; font-family: var(--font-mono); font-size: 0.7rem; letter-spacing: 0.1em; text-transform: uppercase; z-index: 2; }
        .hero-ticker { position: absolute; bottom: 2rem; left: 2rem; right: 2rem; z-index: 2; }
        .ticker-label { font-family: var(--font-mono); font-size: 0.65rem; letter-spacing: 0.1em; text-transform: uppercase; color: var(--gray-mid); margin-bottom: 0.5rem; }
        .ticker-items { display: flex; gap: 0.75rem; flex-wrap: wrap; }
        .ticker-item { background: rgba(242,239,230,0.08); border: 1px solid rgba(242,239,230,0.1); padding: 0.3rem 0.7rem; font-size: 0.75rem; font-weight: 500; border-radius: 2px; color: var(--gray-light); }
        
        .marquee-wrap { border-top: 1px solid rgba(242,239,230,0.08); border-bottom: 1px solid rgba(242,239,230,0.08); background: rgba(232,82,26,0.05); overflow: hidden; padding: 1rem 0; }
        .marquee-track { display: flex; gap: 0; animation: marquee 20s linear infinite; white-space: nowrap; }
        .marquee-track span { font-family: var(--font-display); font-size: 1rem; letter-spacing: 0.14em; color: rgba(232,82,26,0.5); padding: 0 2rem; text-transform: uppercase; }
        .marquee-track span.dot { color: var(--orange); padding: 0; }
        @keyframes marquee { from { transform: translateX(0); } to { transform: translateX(-50%); } }
        
        .section-home { padding: 7rem 4rem; }
        .kategori-home { background: #0D0D0B; }
        .kategori-header { display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 4rem; }
        .kategori-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5px; }
        .kategori-card { position: relative; background: #141410; aspect-ratio: 3/4; overflow: hidden; cursor: pointer; }
        .kategori-card::before { content: ''; position: absolute; inset: 0; background: linear-gradient(to top, rgba(10,10,8,0.95) 0%, rgba(10,10,8,0.2) 60%, transparent 100%); z-index: 1; transition: opacity 0.3s; }
        .kategori-card:hover::before { background: linear-gradient(to top, rgba(232,82,26,0.85) 0%, rgba(10,10,8,0.3) 70%, transparent 100%); }
        .kategori-icon-wrap { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -60%); z-index: 0; opacity: 0.12; transition: opacity 0.3s, transform 0.3s; }
        .kategori-icon-wrap svg { width: 140px; height: 140px; fill: var(--off-white); }
        .kategori-info { position: absolute; bottom: 0; left: 0; right: 0; padding: 1.5rem 1.25rem; z-index: 2; }
        .kategori-name { font-family: var(--font-display); font-size: 1.6rem; letter-spacing: 0.04em; line-height: 1; margin-bottom: 0.35rem; }
        .kategori-count { font-family: var(--font-mono); font-size: 0.65rem; letter-spacing: 0.1em; color: var(--gray-mid); text-transform: uppercase; }
        .kategori-arrow { position: absolute; top: 1.25rem; right: 1.25rem; width: 36px; height: 36px; border: 1px solid rgba(242,239,230,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 2; opacity: 0; transform: translateY(4px); transition: all 0.25s; }
        .kategori-card:hover .kategori-arrow { opacity: 1; transform: translateY(0); border-color: var(--off-white); }
        
        .produk-featured { background: var(--black); }
        .produk-header { display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 3.5rem; }
        .produk-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1px; }
        .produk-card { background: #0F0F0D; padding: 1.75rem; position: relative; overflow: hidden; transition: background 0.25s; text-decoration: none; color: inherit; display: flex; flex-direction: column; }
        .produk-card:hover { background: #171713; }
        .produk-card::after { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: var(--orange); transform: scaleX(0); transform-origin: left; transition: transform 0.3s; }
        .produk-card:hover::after { transform: scaleX(1); }
        .produk-brand { font-family: var(--font-mono); font-size: 0.62rem; letter-spacing: 0.12em; text-transform: uppercase; color: var(--orange); margin-bottom: 0.5rem; }
        .produk-img-wrap { aspect-ratio: 4/3; background: #1A1A16; border-radius: 2px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; overflow: hidden; position: relative; }
        .produk-img-placeholder { opacity: 0.18; }
        .produk-badge { position: absolute; top: 0.75rem; left: 0.75rem; background: var(--orange); color: var(--off-white); font-family: var(--font-mono); font-size: 0.6rem; letter-spacing: 0.08em; padding: 0.25rem 0.6rem; text-transform: uppercase; }
        .produk-name { font-weight: 600; font-size: 0.95rem; margin-bottom: 0.4rem; line-height: 1.4; }
        .produk-spec { font-size: 0.78rem; color: var(--gray-mid); margin-bottom: 1rem; line-height: 1.5; }
        .produk-footer { margin-top: auto; display: flex; align-items: center; justify-content: space-between; }
        .produk-price { font-family: var(--font-display); font-size: 1.5rem; letter-spacing: 0.02em; color: var(--off-white); }
        .produk-price-old { font-size: 0.78rem; color: var(--gray-mid); text-decoration: line-through; margin-bottom: 0.1rem; }
        .produk-add { width: 36px; height: 36px; border: 1px solid rgba(242,239,230,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; background: transparent; color: var(--off-white); cursor: pointer; transition: all 0.2s; flex-shrink: 0; }
        .produk-add:hover { background: var(--orange); border-color: var(--orange); }
        
        .promo-section { padding: 0 4rem 7rem; }
        .promo-banner { background: var(--orange); padding: 4rem; display: grid; grid-template-columns: 1fr auto; gap: 3rem; align-items: center; position: relative; overflow: hidden; }
        .promo-banner::before { content: 'SALE'; position: absolute; right: -2rem; top: 50%; transform: translateY(-50%); font-family: var(--font-display); font-size: 18rem; color: rgba(255,255,255,0.08); pointer-events: none; line-height: 1; }
        .promo-eyebrow { font-family: var(--font-mono); font-size: 0.7rem; letter-spacing: 0.12em; text-transform: uppercase; color: rgba(242,239,230,0.7); margin-bottom: 0.75rem; }
        .promo-title { font-family: var(--font-display); font-size: clamp(2.5rem, 4vw, 4.5rem); line-height: 0.95; letter-spacing: 0.02em; color: var(--off-white); margin-bottom: 1.25rem; }
        .promo-sub { color: rgba(242,239,230,0.75); font-size: 1rem; line-height: 1.6; max-width: 420px; }
        .btn-promo { display: inline-flex; align-items: center; gap: 0.6rem; padding: 1rem 2.5rem; background: var(--off-white); color: var(--orange-dark); font-weight: 700; font-size: 0.9rem; text-decoration: none; border-radius: 2px; letter-spacing: 0.04em; white-space: nowrap; transition: all 0.2s; }
        .btn-promo:hover { background: var(--black); color: var(--off-white); }
        
        .keunggulan { background: #0D0D0B; }
        .keunggulan-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1px; margin-top: 4rem; }
        .keunggulan-item { padding: 2.5rem 2rem; background: #111110; border-top: 2px solid transparent; transition: border-color 0.3s; }
        .keunggulan-item:hover { border-color: var(--orange); }
        .keunggulan-icon { width: 48px; height: 48px; margin-bottom: 1.5rem; color: var(--orange); }
        .keunggulan-title { font-weight: 600; font-size: 1rem; margin-bottom: 0.6rem; }
        .keunggulan-desc { font-size: 0.85rem; color: var(--gray-mid); line-height: 1.65; }
        
        .brand-section { padding: 5rem 4rem; background: var(--black); }
        .brand-label { text-align: center; font-family: var(--font-mono); font-size: 0.65rem; letter-spacing: 0.14em; text-transform: uppercase; color: var(--gray-mid); margin-bottom: 3rem; }
        .brand-logos { display: flex; align-items: center; justify-content: center; gap: 4rem; flex-wrap: wrap; }
        .brand-logo { font-family: var(--font-display); font-size: 1.4rem; letter-spacing: 0.12em; color: rgba(242,239,230,0.15); text-transform: uppercase; transition: color 0.2s; }
        .brand-logo:hover { color: rgba(242,239,230,0.45); }
        
        .testimoni { background: #0D0D0B; }
        .testimoni-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5px; margin-top: 4rem; }
        .testimoni-card { background: #111110; padding: 2rem; position: relative; }
        .testi-stars { display: flex; gap: 3px; margin-bottom: 1.25rem; }
        .testi-star { color: var(--orange); font-size: 0.9rem; }
        .testi-quote { font-size: 0.95rem; line-height: 1.7; color: var(--gray-light); margin-bottom: 1.5rem; }
        .testi-footer { display: flex; align-items: center; gap: 0.75rem; }
        .testi-avatar { width: 36px; height: 36px; border-radius: 50%; background: #2A2A26; display: flex; align-items: center; justify-content: center; font-family: var(--font-mono); font-size: 0.65rem; font-weight: 700; color: var(--orange); flex-shrink: 0; }
        .testi-name { font-weight: 600; font-size: 0.85rem; }
        .testi-motor { font-size: 0.72rem; color: var(--gray-mid); margin-top: 0.1rem; }
        
        .newsletter { background: var(--black); padding: 7rem 4rem; display: grid; grid-template-columns: 1fr 1fr; gap: 6rem; align-items: center; }
        .newsletter-title { font-family: var(--font-display); font-size: clamp(3rem, 5vw, 5.5rem); line-height: 0.95; }
        .newsletter-title .line2 { color: var(--orange); }
        .newsletter-sub { margin-top: 1.5rem; color: var(--gray-light); font-size: 1rem; line-height: 1.7; }
        .newsletter-form { display: flex; flex-direction: column; gap: 1rem; }
        .form-row { display: flex; gap: 0; }
        .form-input { flex: 1; padding: 0.9rem 1.25rem; background: #141410; border: 1px solid rgba(242,239,230,0.12); border-right: none; color: var(--off-white); font-family: var(--font-body); font-size: 0.9rem; outline: none; border-radius: 0; transition: border-color 0.2s; }
        .form-input::placeholder { color: var(--gray-mid); }
        .form-input:focus { border-color: rgba(232,82,26,0.5); }
        .form-btn { padding: 0.9rem 1.75rem; background: var(--orange); color: var(--off-white); border: none; font-family: var(--font-body); font-weight: 600; font-size: 0.85rem; letter-spacing: 0.06em; text-transform: uppercase; cursor: pointer; transition: background 0.2s; }
        .form-btn:hover { background: var(--orange-light); }
        .form-note { font-size: 0.75rem; color: var(--gray-mid); }

        /* ── RESPONSIVE RESPONSIVE RESPONSIVE ── */
        @media (max-width: 1024px) {
            .nav { padding: 0 2rem; }
            .hero { grid-template-columns: 1fr; }
            .hero-right { height: 50vw; }
            .kategori-grid { grid-template-columns: repeat(2, 1fr); }
            .produk-grid { grid-template-columns: repeat(2, 1fr); }
            .keunggulan-grid { grid-template-columns: repeat(2, 1fr); }
            .testimoni-grid { grid-template-columns: 1fr; }
            .newsletter { grid-template-columns: 1fr; gap: 3rem; }
            .section-home { padding: 5rem 2rem; }
            .promo-section { padding: 0 2rem 5rem; }
            .brand-section { padding: 4rem 2rem; }
            .newsletter { padding: 5rem 2rem; }
        }
        @media (max-width: 768px) {
            .nav-search { display: none; }
            .page-wrapper { padding: 1.5rem 1rem 3rem; }
            /* Paksa Sub-UI Layout Grid Pecah Jadi 1 Kolom di HP */
            .detail-grid-layout, .cart-layout, .checkout-layout, .account-layout, .shop-layout {
                grid-template-columns: 1fr !important;
                gap: 1.5rem !important;
            }
            .notif-dropdown { width: 300px; right: -60px; }
        }
        @media (max-width: 640px) {
            .kategori-grid, .produk-grid, .keunggulan-grid { grid-template-columns: 1fr; }
            .hero-left { padding: 4rem 1.5rem; }
            .hero-stats { gap: 2rem; }
            .promo-banner { grid-template-columns: 1fr; }
            .promo-banner::before { display: none; }
        }
    </style>
    @stack('styles')
</head>
<body>

    <nav class="nav">
        <a href="{{ route('home') }}" class="nav-logo">PART<span>L</span>YFE</a>
        <div class="nav-search">
            <input type="text" placeholder="Cari sparepart, merek, tipe motor...">
            <button class="nav-search-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            </button>
        </div>
        <div class="nav-spacer"></div>
        <div class="nav-icons">
            <a href="{{ route('produk.index') }}" class="nav-icon-btn" title="Katalog Produk" style="font-size: 0.8rem; font-family: var(--font-mono); font-weight:bold; color:var(--orange); padding: 0 0.25rem;">SHOP</a>
            
            <a href="{{ route('wishlist.index') }}" class="nav-icon-btn" title="Wishlist">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                <span class="nav-badge">3</span>
            </a>
            
            <div class="nav-icon-wrapper">
                <a href="#" class="nav-icon-btn" title="Notifikasi" id="notifTrigger">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" /><path d="M13.73 21a2 2 0 0 1-3.46 0" /></svg>
                    <span class="nav-badge" id="globalNotifBadge">3</span>
                </a>
                <div class="notif-dropdown" id="notifDropdown">
                    <div class="notif-drop-header">
                        <span>NOTIFIKASI</span>
                        <span id="clearAllNotif" style="font-size: 0.65rem; color: var(--orange); cursor: pointer;">MARK ALL AS READ</span>
                    </div>
                    <div class="notif-drop-list">
                        <div class="notif-drop-item unread">
                            <div class="notif-drop-icon success"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div>
                            <div class="notif-drop-body">
                                <div class="notif-drop-title">Pesanan <strong>#PL-98240</strong> berhasil dikirim.</div>
                                <div class="notif-drop-time">10 Menit yang lalu</div>
                            </div>
                            <div class="unread-dot"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <a href="{{ route('cart.index') }}" class="nav-icon-btn" title="Keranjang">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1" /><circle cx="20" cy="21" r="1" /><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" /></svg>
                <span class="nav-badge">2</span>
            </a>
            
            <a href="{{ route('akun') }}" class="nav-avatar" title="Akun Saya">RH</a>
        </div>
    </nav>

    {{-- KONTEN UTAMA DI-RENDER LANGSUNG TANPA PAKSAAN BOX SEBAGAI DEFAULT --}}
    @yield('content')

    <footer class="footer-mini">
        &copy; {{ date('Y') }} PARTLYFE — Sparepart Motor Terpercaya Indonesia
    </footer>

    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notifTrigger = document.getElementById('notifTrigger');
            const notifDropdown = document.getElementById('notifDropdown');
            const globalBadge = document.getElementById('globalNotifBadge');
            const dropItems = document.querySelectorAll('.notif-drop-item');
            const clearAll = document.getElementById('clearAllNotif');

            notifTrigger.addEventListener('click', function(e) {
                e.preventDefault();
                notifDropdown.classList.toggle('show');
            });

            document.addEventListener('click', function(e) {
                if (!notifTrigger.contains(e.target) && !notifDropdown.contains(e.target)) {
                    notifDropdown.classList.remove('show');
                }
            });

            dropItems.forEach(item => {
                item.addEventListener('click', function() {
                    if (this.classList.contains('unread')) {
                        this.classList.remove('unread');
                        const dot = this.querySelector('.unread-dot');
                        if (dot) dot.remove();
                        let currentCount = parseInt(globalBadge.textContent);
                        if (currentCount > 1) { globalBadge.textContent = currentCount - 1; } else { globalBadge.remove(); }
                    }
                });
            });

            if (clearAll) {
                clearAll.addEventListener('click', function() {
                    dropItems.forEach(item => {
                        item.classList.remove('unread');
                        const dot = item.querySelector('.unread-dot');
                        if (dot) dot.remove();
                    });
                    if (globalBadge) globalBadge.remove();
                });
            }
        });
    </script>
</body>
</html>
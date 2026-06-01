<nav>
    <a href="{{ route('home') }}" class="nav-logo">PART<span>L</span>YFE</a>
    <ul class="nav-links">
        <li><a href="{{ route('kategori.index') }}">Kategori</a></li>
        <li><a href="{{ route('produk.index') }}">Produk</a></li>
        <li><a href="{{ route('promo') }}">Promo</a></li>
        <li><a href="{{ route('tentang') }}">Tentang</a></li>
        <li><a href="{{ route('blog.index') }}">Blog</a></li>
    </ul>
    <div class="nav-cta">
        @auth
            <a href="{{ route('keranjang') }}" class="btn-ghost">Keranjang ({{ auth()->user()->keranjangCount() }})</a>
            <a href="{{ route('akun') }}" class="btn-primary">Akun Saya</a>
        @else
            <a href="{{ route('login') }}" class="btn-ghost">Masuk</a>
            <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
        @endauth
    </div>
</nav>
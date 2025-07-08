<!-- resources/views/home.blade.php -->
@extends('layouts.frontend')

@section('content')
<!-- Hero Section dari Yummy -->
<section id="hero" class="hero section light-background">
    <div class="container">
        <div class="row gy-4 justify-content-center justify-content-lg-between">
            <div class="col-lg-5 order-2 order-lg-1 d-flex flex-column justify-content-center">
                <h1 data-aos="fade-up">Deltizen Corner</h1>
                <p data-aos="fade-up" data-aos-delay="100">Tempat nongkrong santai yang menyajikan hidangan lezat.</p>
                <div class="d-flex" data-aos="fade-up" data-aos-delay="200">
                    <a href="/menu" class="btn-get-started">Buat Pesanan</a>
                </div>
            </div>
            <div class="col-lg-5 order-1 order-lg-2 hero-img" data-aos="zoom-out">
                <img src="{{ asset('gambar9.jpg') }}" class="img-fluid animated" alt="Dish" loading="lazy">
            </div>
        </div>
    </div>
</section>

<!-- Menu Section dari Yummy -->
<section id="menu" class="menu section">
    <div class="container section-title" data-aos="fade-up">
        <h2>Promo Makanan Kami</h2>
        <p><span>Lihat</span> <span class="description-title">Hidangan Lezat Kami</span></p>
    </div>
    <div class="container">
        <div class="row gy-5">
            @forelse($products as $product)
                <div class="col-lg-3 col-md-6 menu-item" data-aos="fade-up" data-aos-delay="100">
                    <a href="{{ route('product.detail_front', ['id' => $product->id]) }}" class="glightbox">
                        <img src="{{ asset($product->image) }}" class="menu-img img-fluid" alt="{{ $product->name }}" style="max-height: 200px; max-width: 100%; object-fit: cover;">
                    </a>
                    <h4>{{ $product->name }}</h4>
                    <p class="ingredients">{{ $product->description }}</p>
                    <p class="price">Rp. {{ number_format($product->price, 0, ',', '.') }}</p>
                    <form action="{{ route('cart.store') }}" method="POST" class="mt-2">
                        @csrf
                        <input type="hidden" name="id" value="{{ $product->id }}">
                        <input type="hidden" name="name" value="{{ $product->name }}">
                        <input type="hidden" name="price" value="{{ $product->price }}">
                        <input type="hidden" name="image" value="{{ $product->image }}">
                        <input type="number" name="quantity" min="1" max="100" value="1" style="width: 60px;">
                        <button class="btn-get-started">Tambah ke Keranjang</button>
                    </form>
                </div>
            @empty
                <p class="text-center">Tidak ada promo makanan tersedia saat ini. <a href="/menu">Lihat menu lengkap</a>.</p>
            @endforelse
        </div>
        <!-- Pagination -->
        <div class="pagination mt-5 d-flex justify-content-center" data-aos="fade-up">
            {{ $products->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
</section>
@endsection
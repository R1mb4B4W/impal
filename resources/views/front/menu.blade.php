@extends('layouts.frontend')

@section('content')
<section id="menu" class="menu section">
    <div class="container section-title" data-aos="fade-up">
        <h2>Menu</h2>
        <p><span>Berikut adalah daftar</span> <span class="description-title">menu yang kami tawarkan</span></p>
    </div>
    <div class="container">
        <ul class="nav nav-tabs d-flex justify-content-center" data-aos="fade-up" data-aos-delay="100">
            <li class="nav-item">
                <a class="nav-link {{ Request::is('menu') ? 'active show' : '' }}" href="/menu">
                    <h4>Semua</h4>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('menu/promo') ? 'active show' : '' }}" href="/menu/promo">
                    <h4>Promo</h4>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('menu/minuman') ? 'active show' : '' }}" href="/menu/minuman">
                    <h4>Minuman</h4>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('menu/makanan') ? 'active show' : '' }}" href="/menu/makanan">
                    <h4>Makanan</h4>
                </a>
            </li>
        </ul>
        <div class="row gy-5 mt-5 justify-content-center">
            @foreach ($products as $product)
                <div class="col-lg-4 col-md-6 col-sm-12 menu-item text-center" style="min-height: 400px;" data-aos="fade-up" data-aos-delay="100">
                    <a href="{{ route('product.detail_front', ['id' => $product->id]) }}" class="glightbox">
                        <img src="{{ url($product->image) }}" class="menu-img img-fluid" alt="{{ $product->name }}" style="height: 200px; width: 100%; object-fit: cover;">
                    </a>
                    <h4>{{ $product->name }}</h4>
                    <p class="ingredients">{{ $product->description }}</p>
                    <p class="price">Rp. {{ number_format($product->price, 0, ',', '.') }}</p>
                    <form action="{{ route('cart.store') }}" method="POST" class="mt-2 d-flex justify-content-center">
                        @csrf
                        <input type="hidden" name="id" value="{{ $product->id }}">
                        <input type="hidden" name="name" value="{{ $product->name }}">
                        <input type="hidden" name="price" value="{{ $product->price }}">
                        <input type="hidden" name="image" value="{{ $product->image }}">
                        <input type="hidden" name="quantity" value="1">
                        <button class="btn-get-started" style="width: 180px; height: 48px; border-radius: 60px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);">Tambah ke Keranjang</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
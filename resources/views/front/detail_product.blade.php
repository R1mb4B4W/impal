@extends('layouts.frontend')

@section('content')
<section id="details" class="about section">
    <div class="container section-title" data-aos="fade-up">
        <h2>Detail Produk</h2>
    </div>
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-7" data-aos="fade-up" data-aos-delay="100">
                <img src="{{ url($product->image) }}" class="img-fluid mb-4" alt="{{ $product->name }}">
            </div>
            <div class="col-lg-5" data-aos="fade-up" data-aos-delay="250">
                <div class="content ps-0 ps-lg-5">
                    <h3>{{ $product->name }}</h3>
                    <ul>
                        <li><i class="bi bi-check-circle-fill"></i> <span>Harga: Rp. {{ number_format($product->price, 0) }}</span></li>
                        <li><i class="bi bi-check-circle-fill"></i> <span>Deskripsi: {{ $product->description }}</span></li>
                        <li><i class="bi bi-check-circle-fill"></i> <span>Kategori: {{ $product->category->name }}</span></li>
                        <li><i class="bi bi-check-circle-fill"></i> <span>Stok: {{ $product->stock }} Porsi</span></li>
                        <li><i class="bi bi-check-circle-fill"></i> <span>Waktu Penjemputan: {{ $product->pickup_time }}</span></li>
                    </ul>
                    <a href="/menu" class="btn-get-started"><i class="bi bi-arrow-left"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
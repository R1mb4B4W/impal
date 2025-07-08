@extends('layouts.frontend')

@php
$user = currentCustomer();
@endphp

@section('content')
<section id="cart" class="menu section">
    <div class="container section-title" data-aos="fade-up">
        <h2>Keranjang</h2>
        <p><span>Informasi</span> <span class="description-title">Keranjang Anda</span></p>
    </div>
    <div class="container">
        <!-- Tambahan: Notifikasi Error -->
        @if ($error = Session::get('error'))
            <div class="alert alert-danger text-center" data-aos="fade-up">
                <p>{{ $error }}</p>
            </div>
        @endif
        @if ($message = Session::get('success'))
            <div class="alert alert-success text-center" data-aos="fade-up">
                <p>{{ $message }}</p>
            </div>
        @endif

        <!-- Tambahan: Penanganan Keranjang Kosong -->
        @if($cartItems->isEmpty())
            <div class="text-center py-5" data-aos="fade-up">
                <p class="fs-5">Keranjang Anda kosong. Yuk, mulai belanja!</p>
                <a href="/menu" class="btn-get-started btn-cart-action" style="background-color: #CE1212; color: white; padding: 10px 20px; font-size: 16px; line-height: 1.5; display: inline-flex; align-items: center; justify-content: center; width: 160px; height: 48px; text-align: center; white-space: normal; border-radius: 4px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); border: 2px solid #222;">
                    <i class="bi bi-cart me-2"></i> Lihat Menu
                </a>
            </div>
        @else
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cartItems as $item)
                        <tr data-aos="fade-up">
                            <td>
                                <img src="{{ $item->attributes->image ?? asset('default-image.jpg') }}" class="menu-img img-fluid" alt="{{ $item->name }}" style="max-height: 80px; max-width: 100%; object-fit: cover;">
                            </td>
                            <td>{{ $item->name }}</td>
                            <td>Rp. {{ number_format($item->price, 0) }}</td>
                            <td>
                                <form action="{{ route('cart.update') }}" method="POST" class="d-flex align-items-center">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <input type="number" name="quantity" oninput="this.value = this.value.replace(/[^0-9]/g, '')" min="1" max="100" step="1" value="{{ $item->quantity }}" data-max-stock="{{ $item->attributes->stock ?? 100 }}" class="cart-quantity" style="width: 60px; margin-right: 10px;">
                                    <button type="submit" class="btn-get-started btn-sm" style="background-color: #CE1212; color: white;" aria-label="Perbarui jumlah item">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <form action="{{ route('cart.remove') }}" method="POST" class="cart-remove-form">
                                    @csrf
                                    <input type="hidden" value="{{ $item->id }}" name="id">
                                    <button class="btn-get-started btn-sm" style="background-color: #CE1212; color: white;" aria-label="Hapus {{ $item->name }}" data-item-name="{{ $item->name }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="total text-end mt-4">
                <h3 class="fw-bold" style="font-family: 'Segoe UI', Arial, sans-serif; font-size: 2rem; color: #333;">
                    Total: <span class="fw-bold cart-total" style="color: #CE1212;">Rp. {{ number_format(Cart::getTotal(), 0) }}</span>
                </h3>
            </div>
            <div class="row align-items-center">
                <div class="col-md-4 text-start mb-2 mb-md-0">
                    <a href="/menu" class="btn-get-started btn-cart-action" style="background-color: #CE1212; color: white; border: 2px solid hsl(0, 0%, 19%); padding: 10px 20px; font-size: 16px; line-height: 1.5; display: inline-flex; align-items: center; justify-content: center; width: 160px; height: 48px; text-align: center; white-space: normal; border-radius: 60px; box-shadow: 0 2px 4px rgb(0, 0, 0, 0.2);">
                        <i class="bi bi-arrow-left me-2"></i> Kembali ke Menu
                    </a>
                </div>
                <div class="col-md-4 text-center mb-2 mb-md-0">
                    <form action="{{ route('cart.clear') }}" method="POST" class="d-inline cart-clear-form">
                        @csrf
                        <button class="btn-get-started btn-cart-action" style="background-color: #CE1212; color: white; padding: 10px 20px; font-size: 16px; line-height: 1.5; display: inline-flex; align-items: center; justify-content: center; width: 160px; height: 48px; text-align: center; white-space: nowrap; border-radius: 60px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); border: 2px solid #222;" aria-label="Hapus semua item di keranjang">
                            <i class="bi bi-trash me-2"></i> Hapus Semua
                        </button>
                    </form>
                </div>
                <div class="col-md-4 text-end">
                    @if($user)
                        <form action="{{ route('cart.checkout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-get-started btn-cart-action" style="background-color: #CE1212; color: white; padding: 10px 20px; font-size: 16px; line-height: 1.5; display: inline-flex; align-items: center; justify-content: center; width: 160px; height: 48px; text-align: center; white-space: normal; border-radius: 60px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); border: 2px solid #222;" {{ $cartItems->isEmpty() ? 'disabled' : '' }}>
                                Pesan
                            </button>
                        </form>
                    @else
                        <button type="button" id="checkoutBtn" class="btn-get-started btn-cart-action" style="background-color: #CE1212; color: white; padding: 10px 20px; font-size: 16px; line-height: 1.5; display: inline-flex; align-items: center; justify-content: center; width: 160px; height: 48px; text-align: center; white-space: normal; border-radius: 60px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); border: 2px solid #222;" {{ $cartItems->isEmpty() ? 'disabled' : '' }}>
                            Pesan
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

@unless($user)
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">Lanjutkan Pesan Sebagai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <a href="{{ route('cart.checkout') }}" class="btn-get-started btn-cart-action d-block mb-2" style="background-color: #CE1212; color: white; padding: 10px 20px; font-size: 16px; line-height: 1.5; display: inline-flex; align-items: center; justify-content: center; width: 160px; height: 48px; text-align: center; white-space: normal; border-radius: 4px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); border: 2px solid #222; margin-left: auto; margin-right: auto;">
                    Tamu
                </a>
                <a href="{{ route('login') }}" class="btn-get-started btn-cart-action d-block" style="background-color: #CE1212; color: white; padding: 10px 20px; font-size: 16px; line-height: 1.5; display: inline-flex; align-items: center; justify-content: center; width: 160px; height: 48px; text-align: center; white-space: normal; border-radius: 4px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); border: 2px solid #222; margin-left: auto; margin-right: auto;">
                    Login
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Tambahan: Modal Konfirmasi Hapus -->
<div class="modal fade" id="confirmRemoveModal" tabindex="-1" aria-labelledby="confirmRemoveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmRemoveModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body text-center">
                <p id="confirmRemoveMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="confirmRemoveBtn" class="btn btn-danger">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal Checkout
    const checkoutBtn = document.getElementById('checkoutBtn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function() {
            new bootstrap.Modal(document.getElementById('checkoutModal')).show();
        });
    }

    // Tambahan: Konfirmasi Hapus Item
    const removeButtons = document.querySelectorAll('.cart-remove-form button');
    const confirmRemoveModal = document.getElementById('confirmRemoveModal');
    const confirmRemoveMessage = document.getElementById('confirmRemoveMessage');
    const confirmRemoveBtn = document.getElementById('confirmRemoveBtn');
    let activeForm = null;

    removeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            activeForm = this.closest('form');
            const itemName = this.getAttribute('data-item-name') || 'item ini';
            confirmRemoveMessage.textContent = `Apakah Anda yakin ingin menghapus ${itemName} dari keranjang?`;
            new bootstrap.Modal(confirmRemoveModal).show();
        });
    });

    if (confirmRemoveBtn) {
        confirmRemoveBtn.addEventListener('click', function() {
            if (activeForm) {
                activeForm.submit();
            }
            new bootstrap.Modal(confirmRemoveModal).hide();
        });
    }

    // Tambahan: Konfirmasi Hapus Semua
    const clearForm = document.querySelector('.cart-clear-form');
    if (clearForm) {
        clearForm.addEventListener('submit', function(e) {
            e.preventDefault();
            activeForm = this;
            confirmRemoveMessage.textContent = 'Apakah Anda yakin ingin menghapus semua item dari keranjang?';
            new bootstrap.Modal(confirmRemoveModal).show();
        });
    }
});
</script>
@endunless
@endsection
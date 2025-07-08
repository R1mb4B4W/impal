@extends('layouts.frontend')
@php
    $user = currentCustomer();
@endphp

<head>
    <link rel="stylesheet" href="{{ asset('/css/checkout.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

@section('content')
    <div class="container-fluid">
        <div class="card" style="margin-top: 30px;">
            <h3 class="card-header">Checkout</h3>
            <form id="checkoutForm" role="form" method="POST" action="{{ route('cart.bayar') }}"
                enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <!-- Input Nama -->
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" id="name" name="name"
                            value="{{ old('name', $user ? $user->name : '') }}" placeholder="Masukkan Nama Pemesan"
                            @if ($user) readonly @endif>
                        @error('name')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <br>

                    <!-- Input Nomor Telepon -->
                    <div class="form-group">
                        <label>No telepon yang dapat dihubungi</label>
                        <input type="text" class="form-control" id="address" name="address"
                            placeholder="Masukkan No telepon yang dapat dihubungi" autofocus required minlength="6"
                            pattern="\d{8,18}" maxlength="18" value="{{ session('address', '') }}">
                        @error('address')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>


                    <br>

                    <!-- Input Waktu Penjemputan -->
                    <div class="form-group">
                        <label>Waktu Penjemputan Pesanan</label>
                        <input type="text" class="form-control" id="pickup_time" name="pickup_time"
                            placeholder="Pilih Waktu Penjemputan" style="background-color: white;" required
                            value="{{ session('pickup_time', '') }}">
                        @error('pickup_time')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <br>

                    <!-- Tombol Segera untuk Set Waktu Otomatis -->
                    <div class="form-group">
                        <button type="button" class="btn" id="setNowButton"
                            style="background-color: #796824; color: white;">Segera</button>
                    </div>

                    <br>

                    <!-- Input Catatan -->
                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea class="form-control" name="catatan" id="catatan" placeholder="Masukkan Catatan Pesanan" cols="30"
                            rows="5" maxlength="300">{{ session('catatan', '') }}</textarea>
                    </div>

                    <!-- Tampilkan Total Harga -->
                    <div class="form-group">
                        <label>
                            <h5 class="fw-bold" style="font-family: 'Segoe UI', Arial, sans-serif; font-size: 1.4rem; color: #333;">
                                Total: <span class="fw-bold" style="color: #ff4a4a;">Rp. {{ number_format(Cart::getTotal(), 0) }}</span>
                            </h5>
                        </label>
                    </div>

                    <div class="box-footer" style="background-color: #ffeaea; border: 1px solid #ff4a4a; border-radius: 8px; padding: 20px; margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
                        <!-- Tombol Kembali -->
                        <a href="{{ route('cart.list') }}" class="btn btn-back" style="background-color: #fff; color: #ff4a4a; border: 1px solid #ff4a4a; margin-left: 10px; font-weight: bold;">
                            Kembali
                        </a>
                        <!-- Tombol Lanjutkan -->
                        <button type="submit" class="btn btn-submit" id="submitBtn" style="background-color: #ff4a4a; color: #fff; font-weight: bold;">
                            Lanjutkan
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi flatpickr untuk memilih tanggal dan waktu
            flatpickr("#pickup_time", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minDate: "today", // Membatasi waktu hanya hari ini dan seterusnya
                minTime: "08:00", // Waktu minimum penjemputan
                maxTime: "22:00", // Waktu maksimum penjemputan
                time_24hr: true, // Format waktu 24 jam
                // allowInput: true, // Mengizinkan input manual
                //disable pemilihan waktu menitnya yang manual dibawah waktu saat ini + 10 menit
                disable: [
                    function(date) {
                        const now = new Date();
                        now.setMinutes(now.getMinutes() + 10); // Menambahkan 10 menit ke waktu saat ini
                        return date < now; // Disable any date before now
                    }
                ],
                disable: [
                    // Menonaktifkan tanggal setelah hari ini
                    function(date) {
                        return date > new Date(); // Disable any date in the future beyond today
                    }
                ]

            });

            // Menambahkan fungsi untuk tombol Segera
            document.getElementById('setNowButton').addEventListener('click', function() {
                // Mendapatkan waktu saat ini
                const now = new Date();

                //menambah waktu 10 menit dari waktu asli pemesan
                now.setMinutes(now.getMinutes() + 10);

                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0'); // Menambahkan leading zero
                const day = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const formattedTime = `${year}-${month}-${day} ${hours}:${minutes}`;

                // Menetapkan waktu sekarang pada input waktu penjemputan
                document.getElementById('pickup_time').value = formattedTime;

                // Menampilkan alert sukses menggunakan SweetAlert2
                Swal.fire({
                    icon: 'success',
                    title: 'Waktu Penjemputan Ditetapkan!',
                    text: `Waktu penjemputan telah diatur 10 menit lagi: ${formattedTime}`,
                    confirmButtonText: 'Ok'
                });
            });

            // Validasi form ketika tombol "Lanjutkan" diklik
            document.getElementById('submitBtn').addEventListener('click', function(event) {
                event.preventDefault(); // Mencegah pengiriman form jika validasi gagal

                let valid = true;
                let errorMessage = '';

                // Validasi Nama
                const name = document.getElementById('name').value;
                if (name.trim() === '') {
                    valid = false;
                    errorMessage += 'Nama pemesan tidak boleh kosong.\n';
                }

                // Validasi Nomor Telepon
                const address = document.getElementById('address').value;
                const addressPattern = /^\d{8,18}$/;
                if (!addressPattern.test(address)) {
                    valid = false;
                    errorMessage += 'Pastikan nomor telepon valid.\n';
                }

                // Validasi Waktu Penjemputan
                const pickupTime = document.getElementById('pickup_time').value;
                if (pickupTime.trim() === '') {
                    valid = false;
                    errorMessage += 'Waktu penjemputan tidak boleh kosong.\n';
                }

                // Jika validasi gagal, tampilkan alert error menggunakan SweetAlert2
                if (!valid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Formulir Tidak Valid',
                        text: errorMessage,
                        confirmButtonText: 'Perbaiki'
                    });
                } else {
                    // Kirim form jika validasi berhasil
                    document.getElementById('checkoutForm').submit();
                }
            });
        });
    </script>
@endsection

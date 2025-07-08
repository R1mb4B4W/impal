@extends('layouts.frontend')

@section('content')
<div class="container-fluid" style="background: linear-gradient(135deg, #f1faee 0%, #e5e5e5 100%); min-height: 100vh; padding-top: 100px;">
    <!-- Web UI (Desktop) -->
    <div class="web-ui d-none d-md-block">
        <div class="d-flex justify-content-center">
            <div class="card shadow-lg" style="max-width: 900px; width: 100%; border-radius: 15px; transition: transform 0.3s ease;" data-aos="fade-up">
                <div class="card-header" style="background: #fff; border-bottom: none; padding: 20px;">
                    <h3 class="register-title" style="font-family: 'Amatic SC', cursive; font-size: 48px; color: #1d3557; text-align: center; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1); margin: 0;">Detail Pesanan</h3>
                </div>
                <div class="card-body">
                    @if(isset($order))
                        @foreach($details as $detail)
                            <div class="row align-items-center mb-4">
                                <div class="col-md-3 text-center">
                                    <img src="{{ url($detail->image) }}" alt="{{ $detail->name }}" style="height: 150px; width: 150px; object-fit: cover; border-radius: 10px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);">
                                </div>
                                <div class="col-md-9">
                                    <table class="table table-borderless" style="font-family: 'Roboto', sans-serif; color: #1d3557;">
                                        <tr>
                                            <td class="title" style="font-weight: 500; width: 200px;">Nama Produk</td>
                                            <td style="font-size: 25px;">{{ $detail->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="title" style="font-weight: 500;">Jumlah</td>
                                            <td style="font-size: 25px;">{{ $detail->pivot->quantity ?? 'Tidak tersedia' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="title" style="font-weight: 500;">Subtotal</td>
                                            <td style="font-size: 25px;">Rp. {{ number_format($detail->pivot->subtotal ?? 0, 0) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="title" style="font-weight: 500;">Catatan</td>
                                            <td style="font-size: 25px;">{{ $order->catatan ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="title" style="font-weight: 500;">Status Pengiriman</td>
                                            <td style="font-size: 25px;">
                                                @php
                                                    $statusColor = match($order->detail_status) {
                                                        'menunggu konfirmasi pembayaran' => 'yellow',
                                                        'pesanan sedang disiapkan' => 'orange',
                                                        'pesanan selesai, menunggu konfirmasi penjemputan' => 'blue',
                                                        'selesai' => 'green',
                                                        default => 'gray',
                                                    };
                                                @endphp
                                                <p><i class="bi bi-circle-fill" style="margin-right: 5px; color:{{ $statusColor }};"></i>{{ ucwords($order->detail_status ?? 'Belum diatur') }}</p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <hr style="border-top: 1px solid #ddd;">
                        @endforeach
                    @else
                        <p class="text-center" style="font-family: 'Roboto', sans-serif; color: #1d3557;">Pesanan tidak ditemukan.</p>
                    @endif
                </div>
                <div class="card-footer text-center" style="background: #fff; border-top: none; padding: 20px;">
                    <a href="{{ route('invoice.list') }}" class="btn-register" style="background: #e63946; color: white; padding: 12px 30px; border: none; border-radius: 50px; font-size: 16px; font-weight: 500; text-transform: uppercase; transition: background 0.3s, transform 0.3s; display: inline-block; text-decoration: none;">Kembali</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile UI -->
    <div class="mobile-ui d-md-none">
        <div class="d-flex justify-content-center">
            <div class="card shadow-lg" style="width: 100%; border-radius: 15px; transition: transform 0.3s ease;" data-aos="fade-up">
                <div class="card-header" style="background: #fff; border-bottom: none; padding: 20px;">
                    <h3 class="register-title" style="font-family: 'Amatic SC', cursive; font-size: 36px; color: #1d3557; text-align: center; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1); margin: 0;">Detail Pesanan</h3>
                </div>
                @if(isset($order))
                    @foreach($details as $detail)
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-5 text-center">
                                    <img src="{{ url($detail->image) }}" alt="{{ $detail->name }}" style="height: 125px; width: 125px; object-fit: cover; border-radius: 10px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);">
                                </div>
                                <div class="col-7">
                                    <table class="table table-borderless" style="font-family: 'Roboto', sans-serif; color: #1d3557;">
                                        <tr>
                                            <td class="subtitle" style="font-size: 20px; font-weight: 500;">{{ $detail->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="subtitle" style="font-size: 20px;">{{ $detail->pivot->quantity ?? 'Tidak tersedia' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="subtitle" style="font-size: 20px;">Rp. {{ number_format($detail->pivot->subtotal ?? 0, 0) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="subtitle" style="font-size: 20px;">{{ $order->catatan ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="subtitle" style="font-size: 20px;">
                                                @php
                                                    $statusColor = match($order->detail_status) {
                                                        'menunggu konfirmasi pembayaran' => 'yellow',
                                                        'pesanan sedang disiapkan' => 'orange',
                                                        'pesanan selesai, menunggu konfirmasi penjemputan' => 'blue',
                                                        'selesai' => 'green',
                                                        default => 'gray',
                                                    };
                                                @endphp
                                                <p><i class="bi bi-circle-fill" style="margin-right: 5px; color:{{ $statusColor }};"></i>{{ ucwords($order->detail_status ?? 'Belum diatur') }}</p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr style="border-top: 1px solid #ddd;">
                    @endforeach
                    <div class="text-center" style="padding: 10px;">
                        <h2 style="font-family: 'Roboto', sans-serif; font-size: 25px; color: #1d3557;">Subtotal: Rp. {{ number_format($details->sum(function($detail) { return $detail->pivot->subtotal ?? 0; }), 0) }}</h2>
                    </div>
                @else
                    <div class="card-body">
                        <p class="text-center" style="font-family: 'Roboto', sans-serif; color: #1d3557;">Pesanan tidak ditemukan.</p>
                    </div>
                @endif
                <div class="card-footer text-center" style="background: #fff; border-top: none; padding: 20px;">
                    <a href="{{ route('invoice.list') }}" class="btn-register" style="background: #e63946; color: white; padding: 12px 30px; border: none; border-radius: 50px; font-size: 16px; font-weight: 500; text-transform: uppercase; transition: background 0.3s, transform 0.3s; display: inline-block; text-decoration: none;">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card:hover {
        transform: translateY(-5px);
    }
    .table td {
        padding: 10px 0;
        vertical-align: middle;
    }
</style>
@endsection
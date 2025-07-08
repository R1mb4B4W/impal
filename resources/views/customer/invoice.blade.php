<!-- resources/views/invoice.blade.php -->
@extends('layouts.frontend')
@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/f4cf3b69a5.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('/css/invoice.css') }}">
    <title>Ringkasan Pesanan</title>
</head>
<body>
    <div class="container-fluid" style="margin-top: 40px;">
        <!-- Pesan Sukses atau Peringatan -->
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif
        @if ($message = Session::get('warning'))
            <div class="alert alert-danger alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        <!-- Tabel Ringkasan Pesanan -->
        <div class="card">
            <div class="card-header">
                <h3>Ringkasan Pesanan</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead style="background-color: #FEF9E1;">
                            <tr>
                                <th>ID</th>
                                <th>Nama Penerima</th>
                                <th>Nomor Telepon</th>
                                <th>Total Bayar</th>
                                <th>Tanggal</th>
                                <th>Kode Pesanan</th>
                                <th>Status</th>
                                <th>Status Pengiriman</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $index => $order)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $order->receiver ?? '-' }}</td>
                                    <td>{{ $order->address ?? '-' }}</td>
                                    <td>Rp. {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td>{{ $order->date }}</td>
                                    <td>{{ $order->id }}</td>
                                    <td>
                                        @php
                                            $statusColor = match($order->status) {
                                                'belum bayar' => 'warning',
                                                'menunggu verifikasi' => 'secondary',
                                                'dibayar' => 'success',
                                                default => 'danger',
                                            };
                                        @endphp
                                        <span class="badge badge-{{ $statusColor }}">{{ ucwords($order->status) }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusColor = match($order->detail_status) {
                                                'menunggu konfirmasi pembayaran' => 'warning',
                                                'pesanan sedang disiapkan' => 'info',
                                                'pesanan selesai, menunggu konfirmasi penjemputan' => 'primary',
                                                'selesai' => 'success',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge badge-{{ $statusColor }}">{{ ucwords($order->detail_status ?? 'Belum diatur') }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('invoice.detail', ['id' => $order->id]) }}" class="btn btn-sm btn-primary">Detail</a>
                                            @if($order->status == 'belum bayar')
                                                <a href="{{ route('confirm.index', ['id' => $order->id]) }}" class="btn btn-sm btn-success">Konfirmasi</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9">Tidak ada pesanan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Skrip Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
</body>
</html>
@endsection
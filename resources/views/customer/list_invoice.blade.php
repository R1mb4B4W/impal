<!-- resources/views/list_invoice.blade.php -->
@extends('layouts.frontend')
@section('content')
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/f4cf3b69a5.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('/css/invoice.css') }}">
</head>
<body>
    <div class="desktop">
        <div class="container-fluid" style="margin-top: 40px;">
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

            <div class="card">
                <div class="box-header">
                    <h3>Ringkasan Pesanan</h3>
                </div>
                <div class="box-body">
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
                                    <td>{{ ucwords($order->detail_status ?? 'Belum diatur') }}</td>
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
    </div>
</body>
@endsection
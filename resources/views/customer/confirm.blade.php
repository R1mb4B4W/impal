@extends('layouts.frontend')
<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
  <link href="{{ asset('/css/confirm.css') }}" rel="stylesheet">
</head>

@section('content')
<div class="container-fluid" style="margin-top: 100px;margin-bottom: 30px;" >
    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-block">
        {{-- <button type="button" class="close" data-dismiss="alert">×</button> --}}
        <strong>{{ $message }}</strong>
    </div>
    @endif
    <div class="card" >
        <div class="card-header">
            <h3>Konfirmasi Pembayaran</h3>
        </div>

        <div class="card-body" style="padding: 20px;">

            <form role="form" method="post" action="{{ route('confirm.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="box-body">

                    <div class="form-group">
                        <label>Kode Pesanan</label>
                        <input type="text" class="form-control" value="{{ $order->id }}" id="order_id" name="order_id" placeholder="Masukan Kode Pesanan"  autofocus required readonly>
                    </div>

                    <div class="form-group">
                        <label>Upload Bukti Pembayaran</label>
                        <input type="file" class="form-control" name="image" autofocus required accept=".jpg, .jpeg, .png">
                    </div>

                    <div class="box-footer d-flex justify-content-between">
                        <a href="{{ route('confirm.index', ['id' => $order->id]) }}" class="btn btn-outline-secondary px-4 py-2 fw-semibold">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-danger px-4 py-2 fw-semibold">
                            Lanjut<i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

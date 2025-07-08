@extends('layouts.frontend')

@section('content')
<section id="payment" class="payment section-bg py-5">
  <div class="container" data-aos="fade-up">
    <div class="section-title mb-5">
      <h2 class="fw-bold text-danger" style="font-size:2rem; letter-spacing:2px; text-shadow:0 4px 16px rgba(0,0,0,0.12);">
      Metode Pembayaran
      </h2>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="d-flex justify-content-center gap-3 mb-4" data-aos="fade-up" data-aos-delay="100">
          <button id="qrisBtn" class="btn btn-lg btn-outline-danger active shadow-sm px-5 py-2 fw-semibold" type="button">
            <i class="bi bi-qr-code-scan me-2"></i>Scan QRIS
          </button>
          <button id="cashBtn" class="btn btn-lg btn-outline-danger shadow-sm px-5 py-2 fw-semibold" type="button">
            <i class="bi bi-cash-coin me-2"></i>Tunai
          </button>
        </div>
        <div id="paymentContent" class="text-center p-5 bg-white rounded-4 shadow-sm border" data-aos="fade-up" data-aos-delay="200">
          <p class="mb-3 fs-5 text-secondary">Silakan scan QRIS berikut:</p>
          <img src="{{ asset('barcode Qris.png') }}" alt="QR Code" class="img-fluid rounded-3 border" style="max-height: 200px;">
        </div>
        <div class="d-flex justify-content-between mt-4">
          <a href="{{ route('cart.checkout') }}" class="btn btn-outline-secondary px-4 py-2 fw-semibold">
            <i class="bi bi-arrow-left me-2"></i>Kembali
          </a>
          <a href="{{ route('confirm.index', ['id' => $order->id]) }}" class="btn btn-danger px-4 py-2 fw-semibold">
            Lanjut<i class="bi bi-arrow-right ms-2"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Bootstrap Icons CDN (if not already included) --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script>
const qrisBtn = document.getElementById('qrisBtn');
const cashBtn = document.getElementById('cashBtn');
const paymentContent = document.getElementById('paymentContent');

qrisBtn.addEventListener('click', () => {
  qrisBtn.classList.add("active");
  cashBtn.classList.remove("active");
  qrisBtn.classList.replace('btn-outline-danger', 'btn-danger');
  cashBtn.classList.replace('btn-danger', 'btn-outline-danger');
  paymentContent.innerHTML = `
    <p class="mb-3 fs-5 text-secondary">Silakan scan QRIS berikut:</p>
    <img src="{{ asset('barcode Qris.png') }}" alt="QR Code" class="img-fluid rounded-3 border" style="max-height: 200px;">
  `;
});

cashBtn.addEventListener('click', () => {
  cashBtn.classList.add("active");
  qrisBtn.classList.remove("active");
  cashBtn.classList.replace('btn-outline-danger', 'btn-danger');
  qrisBtn.classList.replace('btn-danger', 'btn-outline-danger');
  paymentContent.innerHTML = `
    <p class="mb-2 fs-5 text-secondary"><i class="bi bi-cash-coin me-2"></i>Bayar dengan uang tunai ke kasir.</p>
    <p class="text-muted">Pastikan Anda menerima bukti pembayaran.</p>
  `;
  setTimeout(() => {
    window.location.href = "{{ route('invoice.list') }}";
  }, 1000);
});
</script>
@endsection
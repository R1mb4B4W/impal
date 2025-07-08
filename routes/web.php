<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ConfirmController;
use App\Http\Controllers\ConfirmAdminController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\PaymentController;


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login_process'])->name('login.submit');
// Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/logout', [LogoutController::class, 'perform'])->name('logout.perform');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register');



// ------------- USER ---------------------
Route::get('/', [HomeController::class, 'welcome'])->name('welcome');
//--home--
Route::get('/menu', [HomeController::class, 'all'])->name('products.index');
Route::get('/menu/semua', [HomeController::class, 'semua'])->name('products.semua');
Route::get('/menu/makanan', [HomeController::class, 'makanan'])->name('products.makanan');
Route::get('/menu/minuman', [HomeController::class, 'minuman'])->name('products.minuman');
Route::get('/menu/promo', [HomeController::class, 'promo'])->name('products.promo');
Route::get('/menu/cari', [HomeController::class, 'cari'])->name('products.cari');
// Route::get('/menu/{category}', [HomeController::class, 'showCategory'])->name('products.category');
// Route::get('/menu/{category}', [HomeController::class, 'showCategory'])
//     ->where('category', '[a-zA-Z0-9-]+') // Memastikan hanya huruf dan angka
//     ->name('products.category');



//--invoice--
Route::get('/invoice', [InvoiceController::class, 'index'])->name('invoice.index');
Route::get('/invoice/list', [InvoiceController::class, 'list'])->name('invoice.list');
Route::get('/invoice/detail/{id}', [InvoiceController::class, 'detail'])->name('invoice.detail');
//--cart--
Route::post('cart', [CartController::class, 'addToCart'])->name('cart.store');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('cart/count', [CartController::class, 'cartCount'])->name('cart.count');
Route::post('update-cart', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('remove', [CartController::class, 'removeCart'])->name('cart.remove');
Route::post('clear', [CartController::class, 'clearAllCart'])->name('cart.clear');
Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::get('/cart', [CartController::class, 'cartList'])->name('cart.list');
Route::post('/cart/checkout', [CartController::class, 'processCheckout'])->name('cart.checkout');
Route::post('/cart/bayar', [CartController::class, 'bayar'])->name('cart.bayar');
Route::get('/cart/guest-login', [CartController::class, 'guestLogin'])->name('cart.guest-login');

Route::get('/confirm/{id}', [ConfirmController::class, 'index'])->name('confirm.index');
Route::post('/confirm/store', [ConfirmController::class, 'store'])->name('confirm.store');

Route::get('/pembayaran/{id}', [HomeController::class, 'pembayaran'])->name('pembayaran');



// --------------- OWNER -----------------------

Route::get('/owner/laporan/pesanan/tercetak', [OwnerController::class, 'cari2'])->name('pesanan.tercetak');

Route::middleware(['role:owner'])->group(function () {

    Route::get('/owner/index', [OwnerController::class, 'index0'])->name('owner.index');
    Route::get('/owner/profil', [OwnerController::class, 'profil'])->name('owner.profil');
    Route::get('/owner/laporan/pesanan/cetak', [OwnerController::class, 'pesanan_cetak'])->name('pesanan.cetak');
    Route::post('/owner/profil/change_password', [OwnerController::class, 'store'])->name('change.password');
    Route::get('/owner/laporan/penjualan', [OwnerController::class, 'penjualan'])->name('owner.laporan_penjualan');
    Route::get('/owner/laporan/penjualan/cetak', [OwnerController::class, 'penjualan_cetak'])->name('penjualan.cetak');
    Route::get('/owner/laporan/pesanan', [OwnerController::class, 'index2'])->name('laporan.data');
    Route::get('/owner/laporan/pesanan/{id}', [OwnerController::class, 'pesananLaporanDetail'])->name('pesanan.data.detail');
    Route::get('/owner/produk', [OwnerController::class, 'index3']);
    Route::get('/owner/pelanggan', [OwnerController::class, 'index4']);
    Route::get('/owner/admin', [OwnerController::class, 'index5']);
    Route::get('/owner/data/produk', [OwnerController::class, 'produkOwner'])->name('produk.data');
    Route::get('/owner/data/admin', [OwnerController::class, 'adminOwner'])->name('admin.data');
    Route::get('/owner/data/pelanggan', [OwnerController::class, 'pelangganOwner'])->name('pelanggan.data');
    Route::get('/owner/data/penjualan', [OwnerController::class, 'penjualanLaporan'])->name('penjualan.data');
    Route::get('/owner/data/pesanan', [OwnerController::class, 'pesananLaporan'])->name('pesanan.data');
    Route::get('/owner/laporan/cari', [OwnerController::class, 'cari']);
    Route::get('/owner/laporan/kategori', [OwnerController::class, 'kategori']);
    Route::get('/order/cetak_pertanggal/{tglawal}/{tglakhir}', [OwnerController::class, 'cetak'])->name('order.cetak_pertanggal');
    Route::post('/owner/data/admin', [OwnerController::class, 'storeAdmin'])
        ->name('admin.store');
    Route::get('/owner/data/admin', [OwnerController::class, 'adminOwner'])
        ->name('owner.dataAdmin');
    // Tambahkan ini untuk simpan admin baru
    Route::post('/owner/data/admin', [OwnerController::class, 'storeAdmin'])
        ->name('owner.storeAdmin');
});


// ----------------- ADMIN ------------------
Route::get('/invoice/list', [InvoiceController::class, 'list'])->name('invoice.list');
Route::get('/order', [OrderController::class, 'index'])->name('admin.order.index');
Route::get('/order/data', [OrderController::class, 'produkData'])->name('order.data');
Route::get('/order/record', [OrderController::class, 'records'])->name('order.record');
Route::get('/order/cetak', [OrderController::class, 'cetak'])->name('order.cetak');
Route::get('/order/detail/{id}', [OrderController::class, 'detail'])->name('admin.order.detail');
Route::get('/invoice/detail/{id}', [OrderController::class, 'invoiceDetail'])->name('invoice.detail');

Route::middleware(['role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/profil/change_password', [AdminController::class, 'store'])->name('admin.password');
    Route::get('/admin/profil', [AdminController::class, 'profil'])->name('admin.profil');

    //--order--
    Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/order', [OrderController::class, 'index'])->name('admin.order.index');
        // Route::get('/order/{id}', [OrderController::class, 'detail'])->name('admin.order.detail');
        Route::post('/order/{id}/update-status', [OrderController::class, 'updateStatus'])->name('admin.order.updateStatus');
        Route::get('/order/data', [OrderController::class, 'produkData'])->name('admin.order.data');
    });
    // Route::middleware(['auth'])->group(function () {
    // Route::get('/invoice/detail/{id}', [OrderController::class, 'invoiceDetail'])->name('invoice.detail');
    // Route::get('/invoice/list', [OrderController::class, 'listInvoice'])->name('invoice.list');
    // });



    //--product--
    Route::get('/product', [ProductController::class, 'index'])->name('product.index');
    Route::get('/product/stoks/{id}', [ProductController::class, 'changeStoks'])->name('change.stoks');
    Route::get('/product/data', [ProductController::class, 'produkData'])->name('product2.data');
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('/product/update/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::get('/product/destroy/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
    Route::get('/product/detail/{id}', [ProductController::class, 'detail'])->name('product.detail');
    Route::get('/product/detail_front/{id}', [HomeController::class, 'detail_front'])->name('product.detail_front');

    //--categories--
    Route::get('/categories', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/categories/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
    Route::post('/categories/update/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::get('/categories/destroy/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
    Route::get('/categories/detail/{id}', [CategoryController::class, 'detail'])->name('category.detail');

    Route::get('/confirmAdmin', [ConfirmAdminController::class, 'index'])->name('confirmAdmin');
    Route::get('/confirmAdmin/detail/{id}', [ConfirmAdminController::class, 'detail'])->name('confirmAdmin.detail');
    Route::get('/confirmAdmin/terima/{order_id}', [ConfirmAdminController::class, 'terima'])->name('confirmAdmin.terima');
    Route::get('/confirmAdmin/tolak/{order_id}', [ConfirmAdminController::class, 'tolak'])->name('confirmAdmin.tolak');
});






// Route untuk halaman konfirmasi admin

// Route::middleware('auth', 'admin')->get('/confirmAdmin', [ConfirmAdminController::class, 'index'])->name('confirmAdmin');
// Route::middleware('auth', 'admin')->get('/confirmAdmin/detail/{id}', [ConfirmAdminController::class, 'detail'])->name('confirmAdmin.detail');
// Route::middleware('auth', 'admin')->get('/confirmAdmin/terima/{order_id}', [ConfirmAdminController::class, 'terima'])->name('confirmAdmin.terima');
// Route::middleware('auth', 'admin')->get('/confirmAdmin/tolak/{order_id}', [ConfirmAdminController::class, 'tolak'])->name('confirmAdmin.tolak');






// OWNER LOGIN
// Route::get('/owner/login', [LoginController::class, 'showOwnerLoginForm'])->name('owner.login');
// Route::post('/owner/login', [LoginController::class, 'ownerLogin'])->name('owner.login.submit');

// // --ADMIN--
// Route::get('/admin', [LoginController::class, 'showAdminLoginForm'])->name('admin.login');
// Route::post('/admin/login', [LoginController::class, 'adminLogin'])->name('admin.login.submit');

// Route::get('/pembayaran',[HomeController::class, 'pembayaran']);
// Route::get('/pembayaran', [HomeController::class, 'pembayaran'])->name('pembayaran');

// Route::prefix('admin')->group(function () {
//     Route::middleware(['auth'])->group(function () {
//         Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
//         Route::get('/profil', [AdminController::class, 'profil'])->name('admin.profil');
//         Route::post('/profil/change_password', [AdminController::class, 'store'])->name('admin.password');
//     });
// });

//statistik penjualan owner
// Route::middleware(['role:admin,owner'])->group(function () {
//     Route::get('/owner/laporan_penjualan', [OwnerController::class, 'laporan_penjualan'])->name('owner.laporan_penjualan');
// });
// Route::get('/cart/bayar', [CartController::class, 'bayar'])->name('cart.bayar');

// Route::post('/cart/bayar', [CartController::class, 'processCheckout'])->name('cart.bayar');

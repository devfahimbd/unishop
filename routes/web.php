<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/sales-chart', [DashboardController::class, 'salesChartData'])->name('dashboard.sales-chart');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Products
    Route::resource('products', ProductController::class);
    Route::get('/products/search/barcode', [ProductController::class, 'barcodeSearch'])->name('products.barcode-search');

    // Customers
    Route::resource('customers', CustomerController::class);
    Route::get('/customers/{customer}/purchase-history', [CustomerController::class, 'purchaseHistory'])->name('customers.purchase-history');

    // Suppliers
    Route::resource('suppliers', SupplierController::class);

    // Purchases
    Route::resource('purchases', PurchaseController::class);

    // POS / Sales
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/add-to-cart', [PosController::class, 'addToCart'])->name('pos.add-to-cart');
    Route::post('/pos/update-cart', [PosController::class, 'updateCart'])->name('pos.update-cart');
    Route::post('/pos/remove-cart', [PosController::class, 'removeFromCart'])->name('pos.remove-cart');
    Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
    Route::post('/pos/clear-cart', [PosController::class, 'clearCart'])->name('pos.clear-cart');
    Route::get('/pos/invoice/{sale}', [PosController::class, 'invoice'])->name('pos.invoice');

    // Expenses
    Route::resource('expenses', ExpenseController::class);
    Route::get('/expenses/report', [ExpenseController::class, 'report'])->name('expenses.report');

    // Reports
    Route::get('/reports/daily-sales', [ReportController::class, 'dailySales'])->name('reports.daily-sales');
    Route::get('/reports/monthly-sales', [ReportController::class, 'monthlySales'])->name('reports.monthly-sales');
    Route::get('/reports/profit', [ReportController::class, 'profit'])->name('reports.profit');
    Route::get('/reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
    Route::get('/reports/low-stock', [ReportController::class, 'lowStock'])->name('reports.low-stock');
});

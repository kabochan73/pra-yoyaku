<?php

use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('courts.index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 一般ユーザー：コート一覧・詳細（ログイン不要）
Route::get('/courts', [CourtController::class, 'index'])->name('courts.index');
Route::get('/courts/{court}', [CourtController::class, 'show'])->name('courts.show');

// 一般ユーザー：予約（ログイン必須）
Route::middleware(['auth'])->group(function () {
    Route::get('/courts/{court}/reserve', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/courts/{court}/reserve', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/mypage', [ReservationController::class, 'mypage'])->name('mypage');
    Route::patch('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
});

// 管理者：コート登録・削除・全予約管理（管理者のみ）
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/courts/create', [CourtController::class, 'create'])->name('courts.create');
    Route::post('/courts', [CourtController::class, 'store'])->name('courts.store');
    Route::delete('/courts/{court}', [CourtController::class, 'destroy'])->name('courts.destroy');
    Route::get('/reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
    Route::patch('/reservations/{reservation}/cancel', [AdminReservationController::class, 'cancel'])->name('reservations.cancel');
});

require __DIR__.'/auth.php';

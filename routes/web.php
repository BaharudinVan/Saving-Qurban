<?php

use App\Http\Controllers\AuthController;
use App\Models\SavingDetail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

Route::get('/tabungan-qurban/{phone}', [App\Http\Controllers\SavingTransactionController::class, 'viewTabungan'])->name('tabungan-qurban');
Route::get('/list-tabungan', [App\Http\Controllers\SavingTransactionController::class, 'listTabungan'])->name('list-tabungan');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('forgot-password');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Route / otomatis cek login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    } else {
        return redirect()->route('login');
    }
});

Route::get('/evidence/{id}', function ($id) {
    $savings = SavingDetail::findOrFail($id);

    // kalau file di disk public
    return response()->file(storage_path('app/public/' . $savings->evidence));

    // kalau file di disk local (private)
    // return response()->file(storage_path('app/' . $savings->evidence));
});


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin');
    })->name('dashboard');
    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::resource('savings', App\Http\Controllers\SavingController::class);
    Route::resource('saving-transaction', App\Http\Controllers\SavingTransactionController::class);
    Route::resource('animal', App\Http\Controllers\AnimalController::class);
});

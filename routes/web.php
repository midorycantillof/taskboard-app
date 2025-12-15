<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Socialite;
use Illuminate\Http\RedirectResponse;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/google-auth/redirect', function (): RedirectResponse{
    return Socialite::driver('google')->redirect();
});

Route::get('/google-auth/callback', function (): RedirectResponse {
    $user_google = Socialite::driver('google')->user();
    $user = User::updateOrCreate(
        [
            'google_id'=> $user_google->Id,
        ],
        [
            'name'=> $user_google->Name,
            'email'=> $user_google->Email,
        ]);
        Auth::login($user);
        return redirect('/dashboard');
});

require __DIR__.'/auth.php';

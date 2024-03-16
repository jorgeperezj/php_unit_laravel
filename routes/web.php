<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RepositoryController;
use App\Http\Controllers\TagController;
use App\Models\Tag;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [RepositoryController::class, 'index']);
Route::get('/', [PageController::class, 'home']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// RUTAS DEL CUROS BÁSICO DE TDD

Route::get('/tags', function () {
    $tags = Tag::latest()->get();
    return view('tags', compact('tags'));
});

Route::post('tags/', [TagController::class, 'store']);
Route::delete('tags/{tag}', [TagController::class, 'destroy']);

// RUTAS DEL CURSO DE TDD

Route::resource('repositories', RepositoryController::class)
    ->middleware('auth');

require __DIR__.'/auth.php';

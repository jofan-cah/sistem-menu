<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Route Admin
Route::middleware(['auth', 'role:Master'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Categories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    // Ingredients
    Route::prefix('ingredients')->name('ingredients.')->group(function () {
        Route::get('/', [IngredientController::class, 'index'])->name('index');
        Route::get('/create', [IngredientController::class, 'create'])->name('create');
        Route::post('/', [IngredientController::class, 'store'])->name('store');
        Route::get('/{ingredient}', [IngredientController::class, 'show'])->name('show');
        Route::get('/{ingredient}/edit', [IngredientController::class, 'edit'])->name('edit');
        Route::put('/{ingredient}', [IngredientController::class, 'update'])->name('update');
        Route::delete('/{ingredient}', [IngredientController::class, 'destroy'])->name('destroy');
    });

    // Recipes
    Route::prefix('recipes')->name('recipes.')->group(function () {
        Route::get('/', [RecipeController::class, 'index'])->name('index');
        Route::get('/create', [RecipeController::class, 'create'])->name('create');
        Route::post('/', [RecipeController::class, 'store'])->name('store');
        Route::get('/{recipe}', [RecipeController::class, 'show'])->name('show');
        Route::get('/{recipe}/edit', [RecipeController::class, 'edit'])->name('edit');
        Route::put('/{recipe}', [RecipeController::class, 'update'])->name('update');
        Route::delete('/{recipe}', [RecipeController::class, 'destroy'])->name('destroy');
    });

    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });
});

Route::get('/cari', [DashboardController::class, 'cari'])->name('karyawan.cari');


// Route Karyawan
Route::middleware(['auth', 'role:Karyawan'])->prefix('karyawan')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('karyawan.dashboard');
});





require __DIR__ . '/auth.php';

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Public\CatalogController;

// Rutas públicas del catálogo
Route::get('/', [CatalogController::class, 'index'])->name('public.home');
Route::get('/catalogo', [CatalogController::class, 'catalog'])->name('public.catalog');
Route::get('/catalogo/{product:slug}', [CatalogController::class, 'show'])->name('public.product.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::view('/admin', 'admin.dashboard')->name('admin.dashboard');
});  

Route::middleware(['auth', 'role:super-admin|admin|editor|viewer'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::view('/', 'admin.dashboard')->name('dashboard');
        Route::resource('users', UserController::class)
            ->except(['show'])
            ->middleware('role:super-admin');
        Route::get('categories', [CategoryController::class, 'index'])
            ->middleware('permission:categories.view')
            ->name('categories.index');

        Route::get('categories/create', [CategoryController::class, 'create'])
            ->middleware('permission:categories.create')
            ->name('categories.create');

        Route::post('categories', [CategoryController::class, 'store'])
            ->middleware('permission:categories.create')
            ->name('categories.store');

        Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])
            ->middleware('permission:categories.update')
            ->name('categories.edit');

        Route::put('categories/{category}', [CategoryController::class, 'update'])
            ->middleware('permission:categories.update')
            ->name('categories.update');

        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])
            ->middleware('permission:categories.delete')
            ->name('categories.destroy');



        Route::get('products', [ProductController::class, 'index'])
            ->middleware('permission:products.view')
            ->name('products.index');

        Route::get('products/featured/order', [\App\Http\Controllers\Admin\FeaturedProductController::class, 'index'])
            ->name('products.featured.index');
            
        Route::post('products/featured/order', [\App\Http\Controllers\Admin\FeaturedProductController::class, 'update'])
            ->name('products.featured.update');

        Route::get('products/create', [ProductController::class, 'create'])
            ->middleware('permission:products.create')
            ->name('products.create');

        Route::post('products', [ProductController::class, 'store'])
            ->middleware('permission:products.create')
            ->name('products.store');

        Route::get('products/{product}/edit', [ProductController::class, 'edit'])
            ->middleware('permission:products.update')
            ->name('products.edit');

        Route::put('products/{product}', [ProductController::class, 'update'])
            ->middleware('permission:products.update')
            ->name('products.update');

        Route::delete('products/{product}', [ProductController::class, 'destroy'])
            ->middleware('permission:products.delete')
            ->name('products.destroy');
            
    });
    

require __DIR__.'/auth.php';

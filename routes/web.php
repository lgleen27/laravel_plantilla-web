<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AttributeOptionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\VariantMediaController;

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

        Route::get('attributes', [AttributeController::class, 'index'])
            ->middleware('permission:attributes.view')
            ->name('attributes.index');

        Route::get('attributes/create', [AttributeController::class, 'create'])
            ->middleware('permission:attributes.create')
            ->name('attributes.create');

        Route::post('attributes', [AttributeController::class, 'store'])
            ->middleware('permission:attributes.create')
            ->name('attributes.store');

        Route::get('attributes/{attribute}/edit', [AttributeController::class, 'edit'])
            ->middleware('permission:attributes.update')
            ->name('attributes.edit');

        Route::put('attributes/{attribute}', [AttributeController::class, 'update'])
            ->middleware('permission:attributes.update')
            ->name('attributes.update');

        Route::delete('attributes/{attribute}', [AttributeController::class, 'destroy'])
            ->middleware('permission:attributes.delete')
            ->name('attributes.destroy');

        Route::post('attributes/{attribute}/options', [AttributeOptionController::class, 'store'])
            ->middleware('permission:attributes.update')
            ->name('attributes.options.store');

        Route::put('attributes/{attribute}/options/{option}', [AttributeOptionController::class, 'update'])
            ->middleware('permission:attributes.update')
            ->name('attributes.options.update');

        Route::delete('attributes/{attribute}/options/{option}', [AttributeOptionController::class, 'destroy'])
            ->middleware('permission:attributes.delete')
            ->name('attributes.options.destroy');

        Route::get('products', [ProductController::class, 'index'])
            ->middleware('permission:products.view')
            ->name('products.index');

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

        Route::get('products/{product}/variants', [ProductVariantController::class, 'index'])
            ->middleware('permission:variants.view')
            ->name('products.variants.index');

        Route::get('products/{product}/variants/create', [ProductVariantController::class, 'create'])
            ->middleware('permission:variants.create')
            ->name('products.variants.create');

        Route::post('products/{product}/variants', [ProductVariantController::class, 'store'])
            ->middleware('permission:variants.create')
            ->name('products.variants.store');

        Route::get('products/{product}/variants/{variant}/edit', [ProductVariantController::class, 'edit'])
            ->middleware('permission:variants.update')
            ->name('products.variants.edit');

        Route::put('products/{product}/variants/{variant}', [ProductVariantController::class, 'update'])
            ->middleware('permission:variants.update')
            ->name('products.variants.update');

        Route::delete('products/{product}/variants/{variant}', [ProductVariantController::class, 'destroy'])
            ->middleware('permission:variants.delete')
            ->name('products.variants.destroy');

        Route::get('products/{product}/variants/{variant}/media', [VariantMediaController::class, 'index'])
            ->middleware('permission:variants.view')
            ->name('products.variants.media.index');

        Route::post('products/{product}/variants/{variant}/media', [VariantMediaController::class, 'store'])
            ->middleware('permission:variants.update')
            ->name('products.variants.media.store');

        Route::patch('products/{product}/variants/{variant}/media/{media}/primary', [VariantMediaController::class, 'makePrimary'])
            ->middleware('permission:variants.update')
            ->name('products.variants.media.primary');

        Route::delete('products/{product}/variants/{variant}/media/{media}', [VariantMediaController::class, 'destroy'])
            ->middleware('permission:variants.delete')
            ->name('products.variants.media.destroy');
            
    });
    

require __DIR__.'/auth.php';

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class FeaturedProductController extends Controller
{
    public function index()
    {
        // Obtenemos solo los destacados ordenados por nuestro nuevo campo
        $products = Product::where('is_featured', true)
            ->orderBy('featured_sort_order')
            ->get();

        return view('admin.products.featured', compact('products'));
    }

    public function update(Request $request)
    {
        $order = $request->input('order', []);

        // Actualizamos el orden en la base de datos
        foreach ($order as $index => $productId) {
            Product::where('id', $productId)->update(['featured_sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
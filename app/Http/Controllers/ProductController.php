<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active();
        
        // Filter berdasarkan type jika ada
        if ($request->has('type') && $request->type) {
            $query->ofType($request->type);
        }
        
        // Search berdasarkan nama (case-insensitive)
        if ($request->has('search') && $request->search) {
            $query->where('name', 'ILIKE', '%' . $request->search . '%');
        }
        
        $products = $query->orderBy('created_at', 'desc')->paginate(12);
        $types = Product::distinct()->pluck('type');
        
        return view('products.index', compact('products', 'types'));
    }

    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }
        
        return view('products.show', compact('product'));
    }
}

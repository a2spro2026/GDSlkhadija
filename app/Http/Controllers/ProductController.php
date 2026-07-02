<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('reference', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->boolean('low_stock')) {
            $query->whereColumn('quantity', '<=', 'min_quantity');
        }

        $products = $query->orderBy('name')->paginate(15);
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reference' => 'required|string|max:50|unique:products',
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'location' => 'nullable|string|max:255',
            'unit_price' => 'nullable|numeric|min:0',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Produit ajouté au stock.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'reference' => 'required|string|max:50|unique:products,reference,'.$product->id,
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'min_quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'location' => 'nullable|string|max:255',
            'unit_price' => 'nullable|numeric|min:0',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produit mis à jour.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produit supprimé.');
    }

    public function movement(Request $request, Product $product)
    {
        $validated = $request->validate([
            'type' => 'required|in:entree,sortie',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($validated['type'] === 'sortie' && $product->quantity < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Stock insuffisant. Disponible : '.$product->quantity]);
        }

        StockMovement::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'type' => $validated['type'],
            'quantity' => $validated['quantity'],
            'reason' => $validated['reason'],
            'notes' => $validated['notes'],
        ]);

        $product->update([
            'quantity' => $validated['type'] === 'entree'
                ? $product->quantity + $validated['quantity']
                : $product->quantity - $validated['quantity'],
        ]);

        $label = $validated['type'] === 'entree' ? 'Entrée' : 'Sortie';

        return back()->with('success', "{$label} de {$validated['quantity']} {$product->unit} enregistrée.");
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    private const ORDENES = [
        'nombre_asc'       => ['nombre', 'asc'],
        'nombre_desc'      => ['nombre', 'desc'],
        'precio_desc'      => ['precio', 'desc'],
        'precio_asc'       => ['precio', 'asc'],
        'existencias_desc' => ['existencias', 'desc'],
        'existencias_asc'  => ['existencias', 'asc'],
        'fecha_desc'       => ['created_at', 'desc'],
        'fecha_asc'        => ['created_at', 'asc'],
    ];

    public function index(Request $request)
    {
        $request->validate([
            'q'          => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'integer'],
            'precio_min' => ['nullable', 'numeric', 'min:0'],
            'precio_max' => ['nullable', 'numeric', 'min:0'],
            'estado'     => ['nullable', Rule::in(['disponible', 'agotado'])],
            'orden'      => ['nullable', Rule::in(array_keys(self::ORDENES))],
            'per_page'   => ['nullable', Rule::in([10, 15, 25, 50])],
        ]);

        $perPage = (int) $request->input('per_page', 10);
        $orden = $request->input('orden', 'fecha_desc');
        [$columna, $sentido] = self::ORDENES[$orden];

        $query = Product::with('category') // evita consultar la categoría de cada fila
            ->when($request->filled('q'), fn ($q) =>
                $q->where('nombre', 'ilike', '%' . addcslashes($request->q, '\\%_') . '%'))
            ->when($request->filled('category_id'), fn ($q) =>
                $q->where('category_id', $request->category_id))
            ->when($request->filled('precio_min'), fn ($q) =>
                $q->where('precio', '>=', $request->precio_min))
            ->when($request->filled('precio_max'), fn ($q) =>
                $q->where('precio', '<=', $request->precio_max))
            ->when($request->filled('estado'), fn ($q) =>
                $q->where('estado', $request->estado));

        $columna === 'nombre'
            ? $query->orderByRaw("LOWER(nombre) {$sentido}")
            : $query->orderBy($columna, $sentido);

        $products = $query->orderBy('id')->paginate($perPage)->withQueryString();
        $categories = Category::orderBy('nombre')->get(['id', 'nombre']);

        return view('products.index', compact('products', 'categories', 'orden', 'perPage'));
    }

    public function create()
    {
        $categories = Category::orderBy('nombre')->get(['id', 'nombre']);
        return view('products.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        Product::create($request->validated());

        return redirect()->route('products.index')->with('status', 'Producto registrado correctamente.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('nombre')->get(['id', 'nombre']);
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()->route('products.index')->with('status', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('status', 'Producto eliminado correctamente.');
    }
}
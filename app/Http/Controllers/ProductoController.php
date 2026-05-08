<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = auth()->user()->productos()->latest()->get();

        return Inertia::render('Productos/Index', [
            'productos' => $productos
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:50',
            'descripcion' => 'nullable|string|max:1000',
            'precio' => 'required|integer|min:0',
        ]);

        $request->user()->productos()->create($validated);

        return redirect()->back();
    }

    public function edit(Producto $producto)
    {
        if ($producto->user_id !== auth()->id()) { abort(403); }
        return Inertia::render('Productos/Edit', ['producto' => $producto]);
    }

    public function update(Request $request, Producto $producto)
    {
        if ($producto->user_id !== auth()->id()) { abort(403); }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:50',
            'descripcion' => 'nullable|string|max:1000',
            'precio' => 'required|integer|min:0',
        ]);

        $producto->update($validated);
        return redirect()->route('productos.index');
    }

    public function destroy(Producto $producto)
    {
        if ($producto->user_id !== auth()->id()) { abort(403); }

        try {
            $producto->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->withErrors([
                'producto_relacion' => 'No puedes eliminar este producto porque ya está incluido en facturas emitidas.'
            ]);
        }

        return redirect()->back();
    }
}

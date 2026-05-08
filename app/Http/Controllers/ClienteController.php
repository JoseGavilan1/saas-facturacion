<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClienteController extends Controller
{
    public function index()
    {
        // Traemos solo los clientes del usuario que inició sesión, ordenados por los más recientes
        $clientes = auth()->user()->clientes()->latest()->get();

        // Le enviamos esos datos a una vista de Vue (que crearemos en el siguiente paso)
        return Inertia::render('Clientes/Index', [
            'clientes' => $clientes
        ]);
    }

    public function store(Request $request)
    {
        // 1. Validamos que los datos que lleguen del formulario estén correctos
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'rut' => 'required|string|max:20|unique:clientes,rut',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'giro' => 'nullable|string|max:255',
        ]);

        // 2. Guardamos el cliente asociándolo automáticamente al usuario actual
        $request->user()->clientes()->create($validated);

        // 3. Recargamos la página
        return redirect()->back();
    }

    // Mostrar la pantalla de edición
    public function edit(Cliente $cliente)
    {
        if ($cliente->user_id !== auth()->id()) { abort(403); }

        return Inertia::render('Clientes/Edit', [
            'cliente' => $cliente
        ]);
    }

    // Guardar los cambios actualizados
    public function update(Request $request, Cliente $cliente)
    {
        if ($cliente->user_id !== auth()->id()) { abort(403); }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'rut' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:50',
            'giro' => 'nullable|string|max:255',
        ]);

        $cliente->update($validated);

        return redirect()->route('clientes.index');
    }

    // Eliminar el cliente
    public function destroy(Cliente $cliente)
    {
        if ($cliente->user_id !== auth()->id()) { abort(403); }

        try {
            $cliente->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            // Si la base de datos bloquea la eliminación, devolvemos un mensaje de error
            return redirect()->back()->withErrors([
                'cliente_relacion' => 'No puedes eliminar este cliente porque ya tiene facturas emitidas en el sistema.'
            ]);
        }

        return redirect()->back();
    }
}

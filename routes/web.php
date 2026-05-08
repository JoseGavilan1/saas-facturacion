<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\FacturaController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    // Calculamos las métricas
    $estadisticas = [
        'clientes_count' => $user->clientes()->count(),
        'facturas_count' => $user->facturas()->count(),
        'total_facturado' => $user->facturas()->sum('total'),
    ];

    return Inertia::render('Dashboard', [
        'estadisticas' => $estadisticas
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('clientes', ClienteController::class);
    Route::resource('facturas', FacturaController::class)->only(['index', 'create', 'store', 'destroy', 'download']);
    Route::resource('facturas', FacturaController::class)->only(['index', 'create', 'store']);
    Route::get('facturas/{factura}/download', [FacturaController::class, 'download'])->name('facturas.download');
    Route::resource('productos', ProductoController::class);

});

require __DIR__.'/auth.php';

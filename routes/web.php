<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ColaboradorController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ElementoController;
use App\Http\Controllers\PedidoAdminController;
use App\Http\Controllers\ColaboradorDashboardController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\CsvImportController;

// Rutas de autenticación
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas (solo para usuarios autenticados)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->rol === 'administrador') {
            return view('dashboard.admin');
        }
        return redirect()->route('colaborador.dashboard');
    })->name('dashboard');

    // Módulo del Colaborador
    Route::get('/colaborador/dashboard', [ColaboradorDashboardController::class, 'index'])->name('colaborador.dashboard');
    Route::get('/colaborador/solicitar-elementos', [ColaboradorDashboardController::class, 'solicitarElementos'])->name('colaborador.solicitar');
    Route::post('/colaborador/crear-pedido', [ColaboradorDashboardController::class, 'crearPedido'])->name('colaborador.crear-pedido');
    Route::get('/colaborador/reportes', [ReportesController::class, 'index'])->name('colaborador.reportes');

    // Módulos del Administrador
    Route::middleware('can:manage-colaboradores')->group(function () {
        Route::resource('colaboradores', ColaboradorController::class);
    });

    Route::middleware('can:manage-inventario')->group(function () {
        Route::resource('categorias', CategoriaController::class);
        Route::resource('elementos', ElementoController::class);
        Route::get('/importar-elementos', [CsvImportController::class, 'showImportForm'])->name('csv.import.form');
        Route::post('/importar-elementos', [CsvImportController::class, 'import'])->name('csv.import');

    });

    // Nuevas rutas para la gestión de pedidos por el administrador
    Route::middleware('can:manage-entregas')->group(function () {
        Route::get('pedidos', [PedidoAdminController::class, 'index'])->name('pedidos.index');
        Route::get('pedidos/{pedido}', [PedidoAdminController::class, 'show'])->name('pedidos.show');
        Route::post('pedidos/{pedido}/approve', [PedidoAdminController::class, 'approve'])->name('pedidos.approve');
        Route::post('pedidos/{pedido}/reject', [PedidoAdminController::class, 'reject'])->name('pedidos.reject');

        // Ruta de reportes para el administrador
        Route::get('/reports', [ReportesController::class, 'index'])->name('reports.index');
    });
});

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});
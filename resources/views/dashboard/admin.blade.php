<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome (v6) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <title>Dashboard de Administrador</title>
    <style>
        body { font-family: sans-serif; margin: 2rem; }
        .container { max-width: 960px; margin: auto; }
        h1 { text-align: center; }
        hr { border: 1px solid #eee; margin: 1.5rem 0; }
        .header { display: flex; justify-content: flex-end; align-items: center; }
        .logout-btn { background: none; border: none; color: #dc3545; text-decoration: none; font-weight: bold; cursor: pointer; }
        .module-list {
            list-style: none;
            padding: 0;
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* Ajuste para 3 columnas */
            gap: 1.5rem;
            margin-top: 2rem;
            justify-content: center;
        }
        .card {
            background-color: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 250px;
            width: 250;
        }
        .card .icon {
            width: 100px;
            height: 100px;
            background-color: #f0f0f0;
            border-radius: 50%;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #555;
        }
        .card h3 {
            margin: 0;
            font-size: 1.1rem;
            color: #333;
        }
        .card .btn {
            background-color: #007bff;
            color: white;
            padding: 0.75rem;
            text-decoration: none;
            border-radius: 4px;
            display: block;
            margin-top: 1rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <p>Bienvenido, {{ auth()->user()->nombre_usuario }}! <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="logout-btn">Cerrar Sesión</a></p>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
        <hr>
        <h2>Módulos</h2>
        <ul class="module-list">
            <li>
                <div class="card">
                    <div class="icon"> <i class="fa-solid fa-user-group"></i></div>
                    <h3>Colaboradores</h3>
                    <a href="{{ route('colaboradores.index') }}" class="btn">Administrar</a>
                </div>
            </li>
            <li>
                <div class="card">
                    <div class="icon"><i class="fa-solid fa-layer-group"></i></div>
                    <h3>Categorías</h3>
                    <a href="{{ route('categorias.index') }}" class="btn">Administrar</a>
                </div>
            </li>
            <li>
                <div class="card">
                    <div class="icon"><i class="fa-solid fa-list"></i></div>
                    <h3>Elementos</h3>
                    <a href="{{ route('elementos.index') }}" class="btn">Administrar</a>
                </div>
            </li>
            <li>
                <div class="card">
                    <div class="icon"><i class="fa-solid fa-list-check"></i></div>
                    <h3>Pedidos</h3>
                    <a href="{{ route('pedidos.index') }}" class="btn">Administrar</a>
                </div>
            </li>
            <li>
                <div class="card">
                    <div class="icon"><i class="fa-solid fa-chart-line"></i></div>
                    <h3>Reportes</h3>
                    <a href="{{ route('reports.index') }}" class="btn">Administrar</a>
                </div>
            </li>
            <li>
                <div class="card">
                    <div class="icon"><i class="fa-solid fa-folder-open"></i></div>
                    <h3>Importar Elementos</h3>
                    <a href="{{ route('csv.import.form') }}" class="btn">Importar</a>
                </div>
            </li>
        </ul>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Catálogo')</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        header, footer { background-color: #f5f5f5; padding: 20px; text-align: center; }
        main { padding: 20px; }
        .producto { margin-bottom: 30px; }
    </style>
</head>
<body>

    <header>
        <h1>Mi Tienda Online</h1>
        <nav>
            <a href="/">Inicio</a> |
            <a href="/admin">Administrar</a>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} Mi Tienda. Todos los derechos reservados.</p>
    </footer>

</body>
</html>

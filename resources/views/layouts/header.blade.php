<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Imprenet')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">
    <img src="{{ asset('images/logo.png') }}" alt="Imprenet" height="40">
</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
    <li class="nav-item">
        <a class="nav-link"
           href="https://api.whatsapp.com/send?phone=5491166660040&text=Hola%20estuve%20viendo%20su%20Sitio%20Web%20Quiero%20mas%20informacion"
           target="_blank" rel="noopener noreferrer">
           Contacto
        </a>
    </li>
</ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4">

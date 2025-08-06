<!-- resources/views/productos/index.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Lista de Productos</title>
</head>
<body>
    <h1>Administrar productos</h1>
<a href="{{ route('productos.create') }}">Agregar nuevo producto</a>

@foreach($productos as $producto)
    <div>
        <h2>{{ $producto->nombre }}</h2>
        
        @if($producto->imagen_perfil)
    <img src="{{ asset('storage/' . $producto->imagen_perfil) }}" alt="{{ $producto->nombre }}" width="200">
@endif

        
        <p>{{ $producto->descripcion }}</p>
        <p><strong>Precio:</strong> ${{ $producto->precio }}</p>

        <a href="{{ route('productos.edit', $producto) }}">Editar</a>
        <form method="POST" action="{{ route('productos.destroy', $producto) }}">
            @csrf
            @method('DELETE')
            <button type="submit">Eliminar</button>
        </form>
    </div>
@endforeach

    </ul>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif
</body>
</html>

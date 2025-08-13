<!DOCTYPE html>
<html>
<head>
    <title>Editar Producto</title>
</head>
<body>
    <div class="container">
        <h2>Editar producto</h2>

        <form method="POST" action="{{ route('productos.update', $producto->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label>Nombre:</label><br>
            <input type="text" name="nombre" value="{{ $producto->nombre }}"><br><br>

            <label>Descripción:</label><br>
            <textarea name="descripcion">{{ $producto->descripcion }}</textarea><br><br>

            <label>Precio:</label><br>
            <input type="number" step="0.01" name="precio" value="{{ $producto->precio }}"><br><br>

            <label>Imagen principal actual:</label><br>
            @if($producto->imagen_perfil)
                <img src="{{ asset('storage/' . $producto->imagen_perfil) }}" width="150"><br>
            @endif
            <input type="file" name="imagen_perfil"><br><br>

            <label>Imágenes adicionales:</label><br>
            @if($producto->imagenes_adicionales)
                @foreach ($producto->imagenes_adicionales as $img)
                    <img src="{{ asset('storage/' . $img) }}" width="100">
                @endforeach
                <br>
            @endif
            <input type="file" name="imagenes_adicionales[]" multiple><br><br>

            <button type="submit">Actualizar producto</button>
            <a href="{{ route('productos.index') }}">Cancelar</a>
        </form>
    </div>
</body>
</html>

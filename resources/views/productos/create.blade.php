<!-- resources/views/productos/create.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Crear Producto</title>
    <style>
      .hint{font-size:.9rem; opacity:.8}
    </style>
</head>
<body>
    <h1>Crear nuevo producto</h1>

    <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label>Nombre:</label><br>
        <input type="text" name="nombre" value="{{ old('nombre') }}" required><br><br>

        <label>Descripción:</label><br>
        <textarea name="descripcion">{{ old('descripcion') }}</textarea><br><br>

        <label>Precio:</label><br>
        <input type="number" step="0.01" name="precio" value="{{ old('precio') }}" required>
        <div class="hint">Si tildás “No mostrar precio”, no se mostrará en el modal público.</div>
        <label style="display:block; margin-top:6px;">
          <input type="checkbox" name="ocultar_precio" value="1" {{ old('ocultar_precio') ? 'checked' : '' }}>
          No mostrar precio
        </label>
        <br>

        <label>Imagen principal:</label><br>
        <input type="file" name="imagen_perfil" accept="image/*"><br><br>

        <label>Foto de talles (opcional):</label><br>
        <input type="file" name="talla_foto" accept="image/*">
        <div class="hint">Se podrá ver con el botón “Ver talles” en el modal.</div>
        <br><br>

        <label>Foto de colores (opcional):</label><br>
        <input type="file" name="colores_foto" accept="image/*">
        <div class="hint">Se podrá ver con el botón “Ver colores” en el modal.</div>
        <br><br>

        <label>Imágenes adicionales:</label><br>
        <input type="file" name="imagenes_adicionales[]" multiple accept="image/*">
        <div class="hint">Podés subir varias. En el modal se mostrarán después de la principal y la última subida quedará segunda.</div>
        <br><br>

        <button type="submit">Guardar</button>
        <a href="{{ route('productos.index') }}" style="margin-left:8px;">Volver al listado</a>
    </form>

    {{-- Errores de validación (opcional) --}}
    @if ($errors->any())
        <div style="color:#b00; margin-top:12px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</body>
</html>

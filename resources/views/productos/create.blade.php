<!-- resources/views/productos/create.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Crear Producto</title>
</head>
<body>
    <h1>Crear nuevo producto</h1>

    <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label>Nombre:</label><br>
        <input type="text" name="nombre"><br><br>

        <label>Descripción:</label><br>
        <textarea name="descripcion"></textarea><br><br>

        <label>Precio:</label><br>
        <input type="number" step="0.01" name="precio"><br><br>

        <label>Imagen principal:</label><br>
        <input type="file" name="imagen_perfil"><br><br>

        <label>Imágenes adicionales:</label><br>
        <input type="file" name="imagenes_adicionales[]" multiple><br><br>

        <button type="submit">Guardar</button>
    </form>

    <br>
    <a href="{{ route('productos.index') }}">Volver al listado</a>
</body>
</html>

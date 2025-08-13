<!DOCTYPE html>
<html>
<head>
    <title>Editar Producto</title>
    <meta charset="utf-8">
    <style>
        .thumb { display:inline-block; margin:6px; text-align:center; }
        .thumb img { display:block; width:110px; height:110px; object-fit:cover; border-radius:8px; }
        .thumb label { display:block; margin-top:4px; font-size:.9rem; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Editar producto</h2>

        <form method="POST" action="{{ route('productos.update', $producto->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label>Nombre:</label><br>
            <input type="text" name="nombre" value="{{ old('nombre', $producto->nombre) }}"><br><br>

            <label>Descripción:</label><br>
            <textarea name="descripcion">{{ old('descripcion', $producto->descripcion) }}</textarea><br><br>

            <label>Precio:</label><br>
            <input type="number" step="0.01" name="precio" value="{{ old('precio', $producto->precio) }}"><br><br>

            {{-- Imagen principal --}}
            <label>Imagen principal actual:</label><br>
            @if($producto->imagen_perfil)
                <img src="{{ asset('storage/' . $producto->imagen_perfil) }}" width="150" alt="Imagen principal"><br>
            @else
                <em>Sin imagen principal</em><br>
            @endif
            <small>Reemplazar imagen principal (opcional):</small><br>
            <input type="file" name="imagen_perfil" accept="image/*"><br><br>

            {{-- Imágenes adicionales (actuales + eliminar) --}}
            <label>Imágenes adicionales actuales:</label><br>
            @if(!empty($producto->imagenes_adicionales))
                @foreach ($producto->imagenes_adicionales as $img)
                    <div class="thumb">
                        <img src="{{ asset('storage/' . $img) }}" alt="Imagen adicional">
                        <label>
                            <input type="checkbox" name="eliminar[]" value="{{ $img }}">
                            Eliminar
                        </label>
                    </div>
                @endforeach
                <br style="clear:both;"><br>
            @else
                <em>No hay imágenes adicionales</em><br><br>
            @endif

            {{-- Agregar más imágenes --}}
            <label>Agregar más imágenes (se suman a las existentes):</label><br>
            <input type="file" name="imagenes_adicionales[]" multiple accept="image/*"><br><br>

            <button type="submit">Actualizar producto</button>
            <a href="{{ route('productos.index') }}">Cancelar</a>
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
    </div>
</body>
</html>

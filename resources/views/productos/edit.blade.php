<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Editar Producto</title>
    <style>
        .thumb { display:inline-block; margin:6px; text-align:center; }
        .thumb img { display:block; width:110px; height:110px; object-fit:cover; border-radius:8px; }
        .thumb label { display:block; margin-top:4px; font-size:.9rem; }
        .hint{font-size:.9rem; opacity:.8}
        .row{margin-bottom:14px}
    </style>
</head>
<body>
    <div class="container">
        <h2>Editar producto</h2>

        <form method="POST" action="{{ route('productos.update', $producto->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <label>Nombre:</label><br>
                <input type="text" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
            </div>

            <div class="row">
                <label>Descripción:</label><br>
                <textarea name="descripcion">{{ old('descripcion', $producto->descripcion) }}</textarea>
            </div>

            <div class="row">
                <label>Precio:</label><br>
                <input type="number" step="0.01" name="precio" value="{{ old('precio', $producto->precio) }}" required>
                <div class="hint">Si marcás “No mostrar precio”, no se verá en el modal público.</div>
                <label style="display:block; margin-top:6px;">
                    <input type="checkbox" name="ocultar_precio" value="1" {{ old('ocultar_precio', $producto->ocultar_precio) ? 'checked' : '' }}>
                    No mostrar precio
                </label>
            </div>

            {{-- Imagen principal --}}
            <div class="row">
                <label>Imagen principal actual:</label><br>
                @if($producto->imagen_perfil)
                    <img src="{{ asset('storage/' . $producto->imagen_perfil) }}" width="150" alt="Imagen principal"><br>
                @else
                    <em>Sin imagen principal</em><br>
                @endif
                <small>Reemplazar imagen principal (opcional):</small><br>
                <input type="file" name="imagen_perfil" accept="image/*">
            </div>

            {{-- Foto de talles --}}
            <div class="row">
                <label>Foto de talles:</label><br>
                @if($producto->talla_foto)
                    <img src="{{ asset('storage/'.$producto->talla_foto) }}" width="150" alt="Talles"><br>
                    <label><input type="checkbox" name="eliminar_talla" value="1"> Eliminar foto de talles</label><br>
                @else
                    <em>No hay foto de talles</em><br>
                @endif
                <small>Reemplazar / subir foto de talles (opcional):</small><br>
                <input type="file" name="talla_foto" accept="image/*">
            </div>

            {{-- Foto de colores --}}
            <div class="row">
                <label>Foto de colores:</label><br>
                @if($producto->colores_foto)
                    <img src="{{ asset('storage/'.$producto->colores_foto) }}" width="150" alt="Colores"><br>
                    <label><input type="checkbox" name="eliminar_colores" value="1"> Eliminar foto de colores</label><br>
                @else
                    <em>No hay foto de colores</em><br>
                @endif
                <small>Reemplazar / subir foto de colores (opcional):</small><br>
                <input type="file" name="colores_foto" accept="image/*">
            </div>

            {{-- Imágenes adicionales (actuales + eliminar) --}}
            <div class="row">
                <label>Imágenes adicionales actuales (últimas primero):</label><br>
                @if(!empty($producto->imagenes_adicionales))
                    @foreach (collect($producto->imagenes_adicionales)->reverse()->values() as $img)
                        <div class="thumb">
                            <img src="{{ asset('storage/' . $img) }}" alt="Imagen adicional">
                            <label>
                                <input type="checkbox" name="eliminar[]" value="{{ $img }}">
                                Eliminar
                            </label>
                        </div>
                    @endforeach
                    <br style="clear:both;">
                @else
                    <em>No hay imágenes adicionales</em>
                @endif
            </div>

            {{-- Agregar más imágenes --}}
            <div class="row">
                <label>Agregar más imágenes (se suman a las existentes):</label><br>
                <input type="file" name="imagenes_adicionales[]" multiple accept="image/*">
                <div class="hint">Las nuevas se agregan al final; al mostrar invertimos el orden para que la última quede segunda (después de la principal).</div>
            </div>

            <button type="submit">Actualizar producto</button>
            <a href="{{ route('productos.index') }}" style="margin-left:8px;">Cancelar</a>
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

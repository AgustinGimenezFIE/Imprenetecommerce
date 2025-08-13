<!-- resources/views/productos/create.blade.php -->
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Nuevo producto</title>
  <style>
    :root{ --border:#e5e7eb; --muted:#6b7280; --radius:12px; }
    *{box-sizing:border-box}
    body{font-family:system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Helvetica Neue", Arial; margin:0; padding:24px; color:#111; background:#fafafa}
    h1{margin:0 0 14px}
    .topbar{display:flex; gap:10px; align-items:center; margin-bottom:14px}
    .btn{display:inline-flex; align-items:center; gap:6px; background:#111; color:#fff; padding:9px 12px; border-radius:10px; text-decoration:none; border:none; cursor:pointer}
    .btn.secondary{ background:#f3f4f6; color:#111 }
    .page{max-width:980px; margin:0 auto}
    .card{background:#fff; border:1px solid var(--border); border-radius:var(--radius); padding:16px}
    .grid{display:grid; grid-template-columns:1fr 360px; gap:16px}
    .field{margin-bottom:12px}
    .label{display:block; font-weight:600; margin-bottom:6px}
    .hint{font-size:.86rem; color:var(--muted)}
    input[type="text"], input[type="number"], textarea, select{
      width:100%; padding:10px 12px; border:1px solid var(--border); border-radius:10px; background:#fff; font:inherit;
    }
    textarea{min-height:86px; resize:vertical}
    .check{display:flex; align-items:center; gap:8px; margin-top:6px}
    .section-title{font-weight:700; margin:8px 0 10px}
    .thumbs{display:flex; flex-wrap:wrap; gap:8px}
    .thumb{width:86px; height:86px; border-radius:8px; overflow:hidden; border:1px solid var(--border); background:#f3f4f6}
    .thumb img{width:100%; height:100%; object-fit:cover; display:block}
    .actions{display:flex; gap:10px; justify-content:flex-end; padding-top:10px}
  </style>
</head>
<body>
<div class="page">
  <div class="topbar">
    <h1 style="flex:1">Crear nuevo producto</h1>
    <a class="btn secondary" href="{{ route('color_sets.index') }}">🎨 Sets de colores</a>
    <a class="btn secondary" href="{{ route('productos.index') }}">↩ Volver</a>
  </div>

  <form class="card" action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="grid">
      <div>
        <div class="field">
          <label class="label">Nombre</label>
          <input type="text" name="nombre" value="{{ old('nombre') }}" required>
        </div>

        <div class="field">
          <label class="label">Descripción</label>
          <textarea name="descripcion">{{ old('descripcion') }}</textarea>
        </div>

        <div class="field">
          <label class="label">Precio</label>
          <input type="number" step="0.01" name="precio" value="{{ old('precio') }}" required>
          <label class="check">
            <input type="checkbox" name="ocultar_precio" value="1" {{ old('ocultar_precio') ? 'checked' : '' }}>
            <span> No mostrar precio en público</span>
          </label>
          <div class="hint">Si tildás esta opción, el precio no aparecerá en el modal público.</div>
        </div>

        <div class="field">
          <div class="section-title">Imágenes</div>

          <label class="label">Imagen principal</label>
          <input type="file" name="imagen_perfil" accept="image/*">

          <div class="field" style="margin-top:12px">
            <label class="label">Imágenes adicionales</label>
            <input type="file" name="imagenes_adicionales[]" multiple accept="image/*">
            <div class="hint">La última subida queda segunda en el modal; la principal va primero.</div>
          </div>
        </div>
      </div>

      <div>
        <div class="field">
          <div class="section-title">Talles</div>
          <label class="label">Foto de talles (opcional)</label>
          <input type="file" name="talla_foto" accept="image/*">
          <div class="hint">Se abrirá con el botón “Ver talles”.</div>
        </div>

        <div class="field" style="margin-top:16px">
          <div class="section-title">Colores</div>

          <label class="label">Foto de colores (opcional)</label>
          <input type="file" name="colores_foto" accept="image/*">
          <div class="hint">Si cargás esta imagen, tiene prioridad sobre el set compartido.</div>

          <div class="field" style="margin-top:10px">
            <label class="label">O seleccionar set existente</label>
            <select name="color_set_id">
              <option value="">— Seleccionar set de colores —</option>
              @foreach($colorSets as $set)
                <option value="{{ $set->id }}" {{ old('color_set_id') == $set->id ? 'selected' : '' }}>
                  {{ $set->nombre }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="actions">
      <a class="btn secondary" href="{{ route('productos.index') }}">Cancelar</a>
      <button class="btn" type="submit">💾 Guardar</button>
    </div>

    @if ($errors->any())
      <div style="color:#b91c1c; margin-top:8px">
        <strong>Revisá estos campos:</strong>
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
  </form>
</div>
</body>
</html>

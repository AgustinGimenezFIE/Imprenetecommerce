<!-- resources/views/color_sets/create.blade.php -->
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Nuevo set de colores</title>
  <style>
    :root{ --border:#e5e7eb; --muted:#6b7280; --radius:12px; }
    body{font-family:system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Helvetica Neue", Arial; margin:0; padding:24px; color:#111; background:#fafafa}
    h1{margin:0 0 14px}
    .topbar{display:flex; gap:10px; align-items:center; margin-bottom:14px}
    .btn{display:inline-flex; align-items:center; gap:6px; background:#111; color:#fff; padding:9px 12px; border-radius:10px; text-decoration:none; border:none; cursor:pointer}
    .btn.secondary{ background:#f3f4f6; color:#111 }
    .page{max-width:720px; margin:0 auto}
    .card{background:#fff; border:1px solid var(--border); border-radius:var(--radius); padding:16px}
    .field{margin-bottom:12px}
    .label{display:block; font-weight:600; margin-bottom:6px}
    .hint{font-size:.86rem; color:var(--muted)}
    input[type="text"]{width:100%; padding:10px 12px; border:1px solid var(--border); border-radius:10px; background:#fff; font:inherit}
    .actions{display:flex; gap:10px; justify-content:flex-end; padding-top:10px}
    .preview{margin-top:10px; border:1px solid var(--border); border-radius:10px; overflow:hidden; background:#000; max-height:280px}
    .preview img{width:100%; height:100%; object-fit:contain; display:block}
  </style>
</head>
<body>
<div class="page">
  <div class="topbar">
    <h1 style="flex:1">Nuevo set de colores</h1>
    <a class="btn secondary" href="{{ route('color_sets.index') }}">↩ Volver</a>
  </div>

  <form class="card" method="POST" action="{{ route('color_sets.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="field">
      <label class="label">Nombre del set</label>
      <input type="text" name="nombre" value="{{ old('nombre') }}" required>
      <div class="hint">Ejemplo: “Jersey 24/1”. Podrás asociarlo a distintos productos.</div>
    </div>

    <div class="field">
      <label class="label">Imagen (paleta de colores)</label>
      <input type="file" name="imagen" accept="image/*" required onChange="showPreview(this)">
      <div class="preview" id="prev" style="display:none"><img id="prevImg"></div>
    </div>

    <div class="actions">
      <a class="btn secondary" href="{{ route('color_sets.index') }}">Cancelar</a>
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

<script>
  function showPreview(input){
    const prev = document.getElementById('prev');
    const img  = document.getElementById('prevImg');
    if(input.files && input.files[0]){
      const r = new FileReader();
      r.onload = e => { img.src = e.target.result; prev.style.display='block'; };
      r.readAsDataURL(input.files[0]);
    } else { prev.style.display='none'; img.src=''; }
  }
</script>
</body>
</html>

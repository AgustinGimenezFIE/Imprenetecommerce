<!-- resources/views/color_sets/index.blade.php -->
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Sets de Colores</title>
  <style>
    :root{ --border:#e5e7eb; --radius:12px; --muted:#6b7280; }
    body{font-family:system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Helvetica Neue", Arial; margin:0; padding:24px; color:#111; background:#fafafa}
    .topbar{display:flex; gap:10px; align-items:center; margin-bottom:14px}
    h1{margin:0}
    .btn{display:inline-flex; align-items:center; gap:6px; background:#111; color:#fff; padding:9px 12px; border-radius:10px; text-decoration:none; border:none; cursor:pointer}
    .btn.secondary{ background:#f3f4f6; color:#111 }
    .grid{display:grid; grid-template-columns:repeat(auto-fill, minmax(260px,1fr)); gap:16px}
    .card{background:#fff; border:1px solid var(--border); border-radius:var(--radius); overflow:hidden}
    .cover{height:160px; background:#000}
    .cover img{width:100%; height:100%; object-fit:cover; display:block}
    .meta{padding:10px 12px}
    .name{font-weight:700}
    .actions{display:flex; gap:8px; padding:10px 12px 14px}
    .muted{color:var(--muted); font-size:.9rem}
  </style>
</head>
<body>
  <div class="topbar">
    <h1 style="flex:1">Sets de colores</h1>
    <a class="btn secondary" href="{{ route('productos.index') }}">↩ Volver a productos</a>
    <a class="btn" href="{{ route('color_sets.create') }}">➕ Nuevo set</a>
  </div>

  <div class="grid">
    @forelse($sets as $s)
      <div class="card">
        <div class="cover">
          <img src="{{ asset('storage/'.$s->imagen) }}" alt="{{ $s->nombre }}">
        </div>
        <div class="meta">
          <div class="name">{{ $s->nombre }}</div>
          <div class="muted">ID #{{ $s->id }}</div>
        </div>
        <div class="actions">
          <form method="POST" action="{{ route('color_sets.destroy', $s) }}"
                onsubmit="return confirm('¿Eliminar el set {{ $s->nombre }}? (No borra productos)')">
            @csrf @method('DELETE')
            <button class="btn" style="background:#b91c1c">🗑️ Eliminar</button>
          </form>
        </div>
      </div>
    @empty
      <p class="muted">Todavía no creaste sets. Empezá con “Nuevo set”.</p>
    @endforelse
  </div>
</body>
</html>

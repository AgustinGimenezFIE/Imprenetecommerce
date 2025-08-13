<!-- resources/views/productos/index.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Administrar productos</title>
    <style>
        :root{
          --card-w: 280px;
          --radius: 12px;
          --muted: #666;
          --border: #e5e7eb;
        }
        body{font-family:system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Helvetica Neue", Arial; margin:0; padding:20px; color:#111}
        h1{margin:0 0 14px}
        .topbar{display:flex; gap:10px; align-items:center; margin-bottom:14px}
        .btn{display:inline-flex; align-items:center; gap:6px; background:#111; color:#fff; padding:8px 12px; border-radius:10px; text-decoration:none; border:none; cursor:pointer}
        .btn.secondary{ background:#f3f4f6; color:#111 }
        .grid{display:grid; grid-template-columns:repeat(auto-fill, minmax(var(--card-w), 1fr)); gap:16px}

        .card{border:1px solid var(--border); border-radius:var(--radius); overflow:hidden; background:#fff; display:flex; flex-direction:column}
        .card-head{padding:10px 12px 0}
        .title{font-size:1.05rem; font-weight:700; margin:0 0 8px; display:flex; gap:8px; align-items:center; flex-wrap:wrap}
        .badges{display:flex; gap:6px; flex-wrap:wrap; margin-bottom:6px}
        .badge{font-size:.72rem; padding:4px 8px; border-radius:999px; background:#f3f4f6; color:#111; border:1px solid var(--border)}
        .badge.warn{ background:#fff7ed; color:#9a3412; border-color:#fed7aa }

        .carousel{ position:relative; width:100%; overflow:hidden; background:#111 }
        .track{ display:flex; transition:transform .3s ease; }
        .carousel img.main{ width:100%; height:220px; object-fit:cover; display:block; }
        .navbtn{ position:absolute; top:50%; transform:translateY(-50%); border:none; background:rgba(0,0,0,.45); color:#fff; padding:6px 10px; cursor:pointer; border-radius:8px }
        .navbtn.prev{ left:8px } .navbtn.next{ right:8px }

        .meta{padding:10px 12px; color:#111; display:flex; flex-direction:column; gap:6px}
        .desc{color:#333; font-size:.95rem}
        .muted{color:var(--muted); font-size:.85rem}
        .price{font-weight:700}

        .thumbs{ display:flex; gap:6px; padding:10px 12px 0; flex-wrap:wrap; }
        .thumbs img{ width:42px; height:42px; object-fit:cover; opacity:.7; border:2px solid transparent; border-radius:6px; cursor:pointer }
        .thumbs img.active{ opacity:1; border-color:#333 }

        .extras{display:flex; align-items:center; gap:8px; padding:8px 12px}
        .xbtn{font-size:.85rem; padding:6px 10px; border:1px solid var(--border); background:#fff; border-radius:8px; cursor:pointer}
        .actions{display:flex; gap:8px; padding:10px 12px 12px; border-top:1px solid var(--border); margin-top:auto}

        /* Lightbox para talles/colores */
        .admbox{position:fixed; inset:0; background:rgba(0,0,0,.75); display:none; align-items:center; justify-content:center; z-index:9999; padding:16px}
        .admbox.open{display:flex}
        .admbox-inner{width:min(1000px,100%); background:#111; color:#fff; border-radius:16px; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,.4)}
        .admbox-head{display:flex; align-items:center; justify-content:space-between; padding:12px 14px; border-bottom:1px solid #222}
        .admbox-body{background:#000}
        .admbox-body img{width:100%; max-height:78vh; object-fit:contain; display:block}
        .admbox-close{border:none; background:transparent; color:#fff; font-size:22px; cursor:pointer}
    </style>
</head>
<body>
    <div class="topbar">
        <h1 style="flex:1">Administrar productos</h1>
        <a class="btn" href="{{ route('productos.create') }}">➕ Agregar producto</a>
    </div>

    <div class="grid">
    @foreach($productos as $producto)
        @php
            // adicionales: última primero
            $imgs = collect($producto->imagenes_adicionales ?? [])->reverse()->values();
            // principal siempre primera
            if ($producto->imagen_perfil) { $imgs->prepend($producto->imagen_perfil); }

            $talles = $producto->talla_foto ? asset('storage/'.$producto->talla_foto) : null;
            $colores = $producto->colores_foto ? asset('storage/'.$producto->colores_foto) : null;
        @endphp

        <div class="card">
            <div class="card-head">
                <div class="title">
                    <span>{{ $producto->nombre }}</span>
                </div>
                <div class="badges">
                    @if($producto->ocultar_precio)
                        <span class="badge warn">💡 Oculto en público</span>
                    @endif
                    <span class="badge">ID #{{ $producto->id }}</span>
                    <span class="badge">{{ $imgs->count() }} foto{{ $imgs->count() === 1 ? '' : 's' }}</span>
                    @if($talles)<span class="badge">Talles</span>@endif
                    @if($colores)<span class="badge">Colores</span>@endif
                </div>
            </div>

            @if($imgs->count())
                <div class="carousel" data-width="0">
                    <div class="track">
                        @foreach($imgs as $ruta)
                            <img class="main" src="{{ asset('storage/'.$ruta) }}" alt="{{ $producto->nombre }}">
                        @endforeach
                    </div>
                    @if($imgs->count() > 1)
                        <button class="navbtn prev" type="button" aria-label="Anterior">‹</button>
                        <button class="navbtn next" type="button" aria-label="Siguiente">›</button>
                    @endif
                </div>

                @if($imgs->count() > 1)
                    <div class="thumbs">
                        @foreach($imgs as $k => $ruta)
                            <img data-index="{{ $k }}" src="{{ asset('storage/'.$ruta) }}" alt="thumb {{ $k+1 }}">
                        @endforeach
                    </div>
                @endif
            @else
                <div style="padding:12px" class="muted">Sin imágenes</div>
            @endif

            <div class="meta">
                <div class="price">Precio: ${{ number_format($producto->precio, 2) }}
                    @if($producto->ocultar_precio)
                        <span class="muted"> (no se muestra al público)</span>
                    @endif
                </div>
                @if(!empty($producto->descripcion))
                    <div class="desc">{{ $producto->descripcion }}</div>
                @else
                    <div class="muted">Sin descripción</div>
                @endif
            </div>

            <div class="extras">
                @if($talles)
                    <button class="xbtn show-talles" data-url="{{ $talles }}">Ver talles</button>
                @endif
                @if($colores)
                    <button class="xbtn show-colores" data-url="{{ $colores }}">Ver colores</button>
                @endif
            </div>

            <div class="actions">
                <a class="btn secondary" href="{{ route('productos.edit', $producto) }}">✏️ Editar</a>
                <form method="POST" action="{{ route('productos.destroy', $producto) }}" onsubmit="return confirm('¿Eliminar el producto {{ $producto->nombre }}?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn" style="background:#b91c1c">🗑️ Eliminar</button>
                </form>
            </div>
        </div>
    @endforeach
    </div>

    @if(session('success'))
        <p style="color: green; margin-top:14px;">{{ session('success') }}</p>
    @endif

    <!-- Lightbox admin para talles/colores -->
    <div id="admbox" class="admbox" aria-hidden="true">
      <div class="admbox-inner">
        <div class="admbox-head">
          <strong>Vista</strong>
          <button class="admbox-close" id="abClose" aria-label="Cerrar">✕</button>
        </div>
        <div class="admbox-body"><img id="abImg" alt=""></div>
      </div>
    </div>

    <script>
    // Carousel
    document.querySelectorAll('.carousel').forEach(function(c){
        // ancho dinámico según card
        const W = c.clientWidth || 240;
        c.dataset.width = W;

        const track = c.querySelector('.track');
        const imgs  = track ? track.querySelectorAll('img.main') : [];
        const prev  = c.querySelector('.prev');
        const next  = c.querySelector('.next');
        const thumbs= c.parentElement.querySelectorAll('.thumbs img'); // fuera del .carousel
        let i = 0;

        function update(){
            track.style.transform = 'translateX(' + (-i * W) + 'px)';
            thumbs.forEach(t => {
                const active = Number(t.dataset.index) === i;
                t.classList.toggle('active', active);
            });
        }

        prev?.addEventListener('click', ()=>{ i = (i - 1 + imgs.length) % imgs.length; update(); });
        next?.addEventListener('click', ()=>{ i = (i + 1) % imgs.length; update(); });
        thumbs.forEach(t => t.addEventListener('click', ()=>{ i = Number(t.dataset.index); update(); }));

        // Si cambia el tamaño, re-calcular W
        window.addEventListener('resize', ()=>{
            const newW = c.clientWidth || W;
            c.dataset.width = newW;
            update();
        });

        update();
    });

    // Lightbox para talles/colores
    const admbox = document.getElementById('admbox');
    const abImg = document.getElementById('abImg');
    const abClose = document.getElementById('abClose');

    document.querySelectorAll('.show-talles, .show-colores').forEach(btn=>{
        btn.addEventListener('click', ()=>{
            abImg.src = btn.dataset.url || '';
            admbox.classList.add('open');
            admbox.setAttribute('aria-hidden','false');
            document.documentElement.style.overflow='hidden';
        });
    });
    function closeAdmBox(){
        admbox.classList.remove('open');
        admbox.setAttribute('aria-hidden','true');
        document.documentElement.style.overflow='';
        abImg.src = '';
    }
    abClose.addEventListener('click', closeAdmBox);
    admbox.addEventListener('click', (e)=>{ if(e.target === admbox) closeAdmBox(); });
    window.addEventListener('keydown', (e)=>{ if(e.key === 'Escape' && admbox.classList.contains('open')) closeAdmBox(); });
    </script>
</body>
</html>

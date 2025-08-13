<!-- resources/views/productos/index.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lista de Productos</title>
    <style>
        .card{margin:20px 0; padding:12px; border:1px solid #ddd; border-radius:8px; max-width:560px}
        .carousel{ position:relative; width:240px; overflow:hidden; border-radius:8px; }
        .track{ display:flex; transition:transform .3s ease; }
        .carousel img.main{ width:240px; height:auto; display:block; }
        .navbtn{ position:absolute; top:50%; transform:translateY(-50%); border:none; background:#0008; color:#fff; padding:6px 10px; cursor:pointer; border-radius:8px }
        .navbtn.prev{ left:6px } .navbtn.next{ right:6px }
        .thumbs{ display:flex; gap:6px; margin-top:8px; flex-wrap:wrap; }
        .thumbs img{ width:46px; height:46px; object-fit:cover; opacity:.6; border:2px solid transparent; border-radius:6px; cursor:pointer }
        .thumbs img.active{ opacity:1; border-color:#333 }
    </style>
</head>
<body>
    <h1>Administrar productos</h1>
    <a href="{{ route('productos.create') }}">Agregar nuevo producto</a>

    @foreach($productos as $producto)
        @php
            // Construye la lista de imágenes: primero la principal y luego las adicionales
            $imgs = collect($producto->imagenes_adicionales ?? []);
            if ($producto->imagen_perfil) {
                $imgs->prepend($producto->imagen_perfil);
            }
        @endphp

        <div class="card">
            <h2>{{ $producto->nombre }}</h2>

            @if($imgs->count())
                <div class="carousel" data-width="240">
                    <div class="track">
                        @foreach($imgs as $ruta)
                            <img class="main" src="{{ asset('storage/'.$ruta) }}" alt="{{ $producto->nombre }}">
                        @endforeach
                    </div>

                    @if($imgs->count() > 1)
                        <button class="navbtn prev" type="button" aria-label="Anterior">‹</button>
                        <button class="navbtn next" type="button" aria-label="Siguiente">›</button>
                    @endif

                    @if($imgs->count() > 1)
                        <div class="thumbs">
                            @foreach($imgs as $k => $ruta)
                                <img data-index="{{ $k }}" src="{{ asset('storage/'.$ruta) }}" alt="thumb {{ $k+1 }}">
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <em>Sin imágenes</em>
            @endif

            <p>{{ $producto->descripcion }}</p>
            <p><strong>Precio:</strong> ${{ $producto->precio }}</p>

            <a href="{{ route('productos.edit', $producto) }}">Editar</a>
            <form method="POST" action="{{ route('productos.destroy', $producto) }}" style="display:inline-block; margin-left:8px;">
                @csrf
                @method('DELETE')
                <button type="submit">Eliminar</button>
            </form>
        </div>
    @endforeach

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <script>
    document.querySelectorAll('.carousel').forEach(function(c){
        const W = Number(c.dataset.width || 240);
        const track = c.querySelector('.track');
        const imgs  = track ? track.querySelectorAll('img.main') : [];
        const prev  = c.querySelector('.prev');
        const next  = c.querySelector('.next');
        const thumbs= c.querySelectorAll('.thumbs img');
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

        update();
    });
    </script>
</body>
</html>

@include('layouts.header')

<h2 style="text-align: center; margin-top: 20px;">Catálogo de productos</h2>

<div style="max-width: 1200px; margin: auto; padding: 20px;">
    @foreach($productos as $producto)
        <div style="margin-bottom: 40px; border-bottom: 1px solid #ddd; padding-bottom: 20px;">
            <h3>{{ $producto->nombre }}</h3>

            @if($producto->imagen_perfil)
                <img src="{{ asset('storage/' . $producto->imagen_perfil) }}" alt="{{ $producto->nombre }}" width="200">
            @endif

            <p>{{ $producto->descripcion }}</p>
            <p><strong>Precio:</strong> ${{ $producto->precio }}</p>
        </div>
    @endforeach
</div>

@include('layouts.footer')

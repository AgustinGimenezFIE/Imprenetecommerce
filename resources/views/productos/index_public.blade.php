@extends('layouts.app')

@section('title', 'Catálogo de productos')

@section('content')
    <h2>Catálogo de productos</h2>

    @foreach($productos as $producto)
        <div style="margin-bottom: 30px;">
            <h3>{{ $producto->nombre }}</h3>

            @if($producto->imagen_perfil)
                <img src="{{ asset('storage/' . $producto->imagen_perfil) }}" alt="{{ $producto->nombre }}" width="200">
            @endif

            <p>{{ $producto->descripcion }}</p>
            <p><strong>Precio:</strong> ${{ $producto->precio }}</p>
            <hr>
        </div>
    @endforeach
@endsection

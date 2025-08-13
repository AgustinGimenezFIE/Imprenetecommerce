<?php

// app/Models/Producto.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'imagen_perfil',
        'imagenes_adicionales',
        'ocultar_precio',
        'talla_foto',
        'colores_foto',
    ];

    protected $casts = [
        'imagenes_adicionales' => 'array',
        'ocultar_precio' => 'boolean',
    ];
}

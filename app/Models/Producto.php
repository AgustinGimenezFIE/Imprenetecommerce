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
    ];

    protected $casts = [
        'imagenes_adicionales' => 'array',
    ];
}

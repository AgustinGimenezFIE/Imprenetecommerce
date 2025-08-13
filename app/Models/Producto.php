<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'imagen_perfil',
        'imagenes_adicionales',
        'ocultar_precio',
        'talla_foto',
        'colores_foto',
        'color_set_id',      // <-- nuevo: referencia al set de colores compartido
    ];

    protected $casts = [
        'imagenes_adicionales' => 'array',
        'ocultar_precio'       => 'boolean',
    ];

    /* ----------------- Relaciones ----------------- */

    public function colorSet()
    {
        return $this->belongsTo(ColorSet::class);
    }

    /* ----------------- Helpers opcionales ----------------- */

    /**
     * Devuelve las imágenes ordenadas para carrusel:
     * principal primero + adicionales con la última subida en 2º lugar (reverse).
     */
    public function getImagenesOrdenadasAttribute(): array
    {
        $adicionales = collect($this->imagenes_adicionales ?? [])->reverse()->values();
        if ($this->imagen_perfil) {
            $adicionales->prepend($this->imagen_perfil);
        }
        return $adicionales->all();
    }

    /**
     * URL efectiva de la foto de colores:
     * primero la específica del producto (override),
     * si no hay, la del set compartido; si no, null.
     */
    public function getColoresUrlAttribute(): ?string
    {
        if (!empty($this->colores_foto)) {
            return asset('storage/'.$this->colores_foto);
        }
        if ($this->relationLoaded('colorSet') ? $this->colorSet : $this->colorSet()->exists()) {
            return $this->colorSet && $this->colorSet->imagen
                ? asset('storage/'.$this->colorSet->imagen)
                : null;
        }
        return null;
    }
}

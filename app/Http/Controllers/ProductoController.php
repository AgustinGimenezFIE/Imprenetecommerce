<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\ColorSet;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    /** Listado admin */
    public function index()
    {
        $productos = Producto::with('colorSet')->get();
        return view('productos.index', compact('productos'));
    }

    /** Listado público */
    public function indexPublic()
    {
        $productos = Producto::with('colorSet')->get();
        return view('productos.index_public', compact('productos'));
    }

    /** Form crear */
    public function create()
    {
        $colorSets = ColorSet::orderBy('nombre')->get();
        return view('productos.create', compact('colorSets'));
    }

    /** Guardar nuevo */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'                 => 'required|string|max:255',
            'descripcion'            => 'nullable|string',
            'precio'                 => 'required|numeric',
            'imagen_perfil'          => 'nullable|image|mimes:jpg,jpeg,png',
            'imagenes_adicionales.*' => 'nullable|image|mimes:jpg,jpeg,png',
            'talla_foto'             => 'nullable|image|mimes:jpg,jpeg,png',
            'colores_foto'           => 'nullable|image|mimes:jpg,jpeg,png',
            'ocultar_precio'         => 'nullable|boolean',
            'color_set_id'           => 'nullable|exists:color_sets,id',
        ]);

        $imagenPerfilPath = $request->hasFile('imagen_perfil')
            ? $request->file('imagen_perfil')->store('productos', 'public')
            : null;

        $imagenesAdicionales = [];
        if ($request->hasFile('imagenes_adicionales')) {
            foreach ($request->file('imagenes_adicionales') as $imagen) {
                $imagenesAdicionales[] = $imagen->store('productos', 'public');
            }
        }

        $tallaFoto = $request->hasFile('talla_foto')
            ? $request->file('talla_foto')->store('productos', 'public')
            : null;

        $coloresFoto = $request->hasFile('colores_foto')
            ? $request->file('colores_foto')->store('productos', 'public')
            : null;

        Producto::create([
            'nombre'               => $request->nombre,
            'descripcion'          => $request->descripcion,
            'precio'               => $request->precio,
            'imagen_perfil'        => $imagenPerfilPath,
            'imagenes_adicionales' => $imagenesAdicionales,
            'ocultar_precio'       => $request->boolean('ocultar_precio'),
            'talla_foto'           => $tallaFoto,
            'colores_foto'         => $coloresFoto,
            'color_set_id'         => $request->input('color_set_id'),
        ]);

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    /** Form editar */
    public function edit(Producto $producto)
    {
        $colorSets = ColorSet::orderBy('nombre')->get();
        return view('productos.edit', compact('producto', 'colorSets'));
    }

    /** Actualizar */
    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre'                 => 'required|string|max:255',
            'descripcion'            => 'nullable|string',
            'precio'                 => 'required|numeric',
            'imagen_perfil'          => 'nullable|image|mimes:jpg,jpeg,png',
            'imagenes_adicionales.*' => 'nullable|image|mimes:jpg,jpeg,png',
            'talla_foto'             => 'nullable|image|mimes:jpg,jpeg,png',
            'colores_foto'           => 'nullable|image|mimes:jpg,jpeg,png',
            'ocultar_precio'         => 'nullable|boolean',
            'eliminar_talla'         => 'nullable|boolean',
            'eliminar_colores'       => 'nullable|boolean',
            'color_set_id'           => 'nullable|exists:color_sets,id',
        ]);

        $producto->nombre         = $request->nombre;
        $producto->descripcion    = $request->descripcion;
        $producto->precio         = $request->precio;
        $producto->ocultar_precio = $request->boolean('ocultar_precio');
        $producto->color_set_id   = $request->input('color_set_id');

        // Imagen principal
        if ($request->hasFile('imagen_perfil')) {
            if ($producto->imagen_perfil) {
                Storage::disk('public')->delete($producto->imagen_perfil);
            }
            $producto->imagen_perfil = $request->file('imagen_perfil')->store('productos', 'public');
        }

        // Adicionales: eliminar seleccionadas
        $existentes = collect($producto->imagenes_adicionales ?? []);
        $aEliminar  = collect($request->input('eliminar', [])); // rutas
        if ($aEliminar->isNotEmpty()) {
            foreach ($aEliminar as $ruta) {
                Storage::disk('public')->delete($ruta);
            }
            $existentes = $existentes->reject(fn($ruta) => in_array($ruta, $aEliminar->all()));
        }

        // Adicionales: agregar nuevas
        if ($request->hasFile('imagenes_adicionales')) {
            foreach ($request->file('imagenes_adicionales') as $img) {
                $existentes->push($img->store('productos', 'public'));
            }
        }
        $producto->imagenes_adicionales = $existentes->values()->all();

        // Talles
        if ($request->boolean('eliminar_talla') && $producto->talla_foto) {
            Storage::disk('public')->delete($producto->talla_foto);
            $producto->talla_foto = null;
        }
        if ($request->hasFile('talla_foto')) {
            if ($producto->talla_foto) {
                Storage::disk('public')->delete($producto->talla_foto);
            }
            $producto->talla_foto = $request->file('talla_foto')->store('productos', 'public');
        }

        // Colores (propios del producto; NO tocar el set)
        if ($request->boolean('eliminar_colores') && $producto->colores_foto) {
            Storage::disk('public')->delete($producto->colores_foto);
            $producto->colores_foto = null;
        }
        if ($request->hasFile('colores_foto')) {
            if ($producto->colores_foto) {
                Storage::disk('public')->delete($producto->colores_foto);
            }
            $producto->colores_foto = $request->file('colores_foto')->store('productos', 'public');
        }

        $producto->save();

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    /** Eliminar */
    public function destroy(Producto $producto)
    {
        // Borrar archivos propios del producto
        if ($producto->imagen_perfil)  Storage::disk('public')->delete($producto->imagen_perfil);
        if ($producto->talla_foto)     Storage::disk('public')->delete($producto->talla_foto);
        if ($producto->colores_foto)   Storage::disk('public')->delete($producto->colores_foto);

        if ($producto->imagenes_adicionales) {
            foreach ($producto->imagenes_adicionales as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        $producto->delete();

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}

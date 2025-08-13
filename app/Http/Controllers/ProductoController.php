<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::all();
        return view('productos.index', compact('productos'));
    }

    public function indexPublic()
    {
        $productos = Producto::all();
        return view('productos.index_public', compact('productos'));
    }

    public function create()
    {
        return view('productos.create');
    }

    // app/Http/Controllers/ProductoController.php

// Crear
public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'precio' => 'required|numeric',
        'imagen_perfil' => 'nullable|image|mimes:jpg,jpeg,png',
        'imagenes_adicionales.*' => 'nullable|image|mimes:jpg,jpeg,png',
        'talla_foto' => 'nullable|image|mimes:jpg,jpeg,png',
        'colores_foto' => 'nullable|image|mimes:jpg,jpeg,png',
        'ocultar_precio' => 'nullable|boolean',
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
    ]);

    return redirect()->route('productos.index')->with('success', 'Producto creado correctamente.');
}

/** NUEVO: Editar (Route Model Binding) */
public function edit(Producto $producto)
{
    return view('productos.edit', compact('producto'));
}

/** ACTUALIZADO: Update con Producto $producto (no con $id) */
public function update(Request $request, Producto $producto)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'precio' => 'required|numeric',
        'imagen_perfil' => 'nullable|image|mimes:jpg,jpeg,png',
        'imagenes_adicionales.*' => 'nullable|image|mimes:jpg,jpeg,png',
        'talla_foto' => 'nullable|image|mimes:jpg,jpeg,png',
        'colores_foto' => 'nullable|image|mimes:jpg,jpeg,png',
        'ocultar_precio' => 'nullable|boolean',
        'eliminar_talla' => 'nullable|boolean',
        'eliminar_colores' => 'nullable|boolean',
    ]);

    $producto->nombre = $request->nombre;
    $producto->descripcion = $request->descripcion;
    $producto->precio = $request->precio;
    $producto->ocultar_precio = $request->boolean('ocultar_precio');

    // Imagen principal
    if ($request->hasFile('imagen_perfil')) {
        if ($producto->imagen_perfil) {
            Storage::disk('public')->delete($producto->imagen_perfil);
        }
        $producto->imagen_perfil = $request->file('imagen_perfil')->store('productos', 'public');
    }

    // Eliminar adicionales marcadas
    $existentes = collect($producto->imagenes_adicionales ?? []);
    $aEliminar = collect($request->input('eliminar', [])); // rutas
    if ($aEliminar->isNotEmpty()) {
        foreach ($aEliminar as $ruta) {
            Storage::disk('public')->delete($ruta);
        }
        $existentes = $existentes->reject(fn($ruta) => in_array($ruta, $aEliminar->all()));
    }

    // Agregar nuevas adicionales
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

    // Colores
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

    return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
}

/** NUEVO: Destroy (Route Model Binding) */
public function destroy(Producto $producto)
{
    // borrar imágenes
    if ($producto->imagen_perfil) {
        Storage::disk('public')->delete($producto->imagen_perfil);
    }
    if ($producto->talla_foto) {
        Storage::disk('public')->delete($producto->talla_foto);
    }
    if ($producto->colores_foto) {
        Storage::disk('public')->delete($producto->colores_foto);
    }
    if ($producto->imagenes_adicionales) {
        foreach ($producto->imagenes_adicionales as $img) {
            Storage::disk('public')->delete($img);
        }
    }

    $producto->delete();

    return redirect()->route('productos.index')->with('success', 'Producto eliminado correctamente.');
}
}
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

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'imagen_perfil' => 'nullable|image|mimes:jpg,jpeg,png',
            'imagenes_adicionales.*' => 'nullable|image|mimes:jpg,jpeg,png'
        ]);

        $imagenPerfilPath = null;
        if ($request->hasFile('imagen_perfil')) {
            $imagenPerfilPath = $request->file('imagen_perfil')->store('productos', 'public');
        }

        $imagenesAdicionales = [];
        if ($request->hasFile('imagenes_adicionales')) {
            foreach ($request->file('imagenes_adicionales') as $imagen) {
                $imagenesAdicionales[] = $imagen->store('productos', 'public');
            }
        }

        Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'imagen_perfil' => $imagenPerfilPath,
            'imagenes_adicionales' => $imagenesAdicionales,
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente.');
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'imagen_perfil' => 'nullable|image|mimes:jpg,jpeg,png',
            'imagenes_adicionales.*' => 'nullable|image|mimes:jpg,jpeg,png'
        ]);

        $producto = Producto::findOrFail($id);

        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->precio = $request->precio;

        if ($request->hasFile('imagen_perfil')) {
            if ($producto->imagen_perfil) {
                Storage::disk('public')->delete($producto->imagen_perfil);
            }
            $producto->imagen_perfil = $request->file('imagen_perfil')->store('productos', 'public');
        }

        if ($request->hasFile('imagenes_adicionales')) {
            if ($producto->imagenes_adicionales) {
                foreach ($producto->imagenes_adicionales as $img) {
                    Storage::disk('public')->delete($img);
                }
            }

            $nuevas = [];
            foreach ($request->file('imagenes_adicionales') as $img) {
                $nuevas[] = $img->store('productos', 'public');
            }

            $producto->imagenes_adicionales = $nuevas;
        }

        $producto->save();

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);

        if ($producto->imagen_perfil) {
            Storage::disk('public')->delete($producto->imagen_perfil);
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

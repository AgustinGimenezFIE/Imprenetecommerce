<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ColorSet;
use Illuminate\Support\Facades\Storage;

class ColorSetController extends Controller
{
    public function index()
    {
        $sets = ColorSet::orderBy('created_at', 'desc')->get();
        return view('color_sets.index', compact('sets')); // <- ENVÍA $sets
    }

    public function create()
    {
        return view('color_sets.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'imagen' => 'required|image|mimes:jpg,jpeg,png',
        ]);

        $path = $request->file('imagen')->store('color_sets', 'public');

        ColorSet::create([
            'nombre' => $data['nombre'],
            'imagen' => $path,
        ]);

        return redirect()->route('color_sets.index')->with('success', 'Set creado.');
    }

    public function destroy(ColorSet $colorSet)
    {
        if ($colorSet->imagen) {
            Storage::disk('public')->delete($colorSet->imagen);
        }
        $colorSet->delete();

        return back()->with('success', 'Set eliminado.');
    }
}

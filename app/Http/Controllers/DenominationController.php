<?php

namespace App\Http\Controllers;

use App\Models\Denomination;
use Illuminate\Http\Request;

class DenominationController extends Controller
{
    public function index()
    {
        $denominations = Denomination::all();
        return view('denominations.admin', compact('denominations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order' => 'nullable|integer',
            'acronym' => 'nullable|string|max:255',
            'denomination' => 'nullable|string|max:255',
        ]);

        // Si el campo 'order' está vacío, obtener el número menor disponible
        if ($request->input('order') === null || $request->input('order')=="") {
            $validated['order'] = (Denomination::max('order') ?? 0) + 1; // Si no hay registros, asigna 1
        }

        // Verificar si el valor de 'order' ya existe
        if (Denomination::where('order', $validated['order'])->exists()) {
            return redirect()->back()->withErrors(['order' => 'El campo "order" ya existe en la tabla.']);
        }

   
        Denomination::create($validated);
        return redirect()->route('denominations.admin')->with('success', 'Denominación creada correctamente.');
    }

    public function edit(Denomination $denomination)
    {
        return view('denominations.edit', compact('denomination'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'order' => 'nullable|integer',
            'acronym' => 'nullable|string|max:255',
            'denomination' => 'nullable|string|max:255',
        ]);

        $denomination = Denomination::find($request->input('id')); 
        if(!($denomination)){
            return redirect()->back()->withErrors(['order' => 'El elemento ya no existe.']);
        }
            // Si el campo 'order' está vacío, obtener el número menor disponible
            if ($request->input('order') === null || $request->input('order')=="") {
                $validated['order'] = (Denomination::max('order') ?? 0) + 1; // Si no hay registros, asigna 1
            }
    

        // Verificar si el valor de 'order' ya existe y no corresponde al registro actual
        if (Denomination::where('order', $validated['order'])->where('id', '!=', $denomination->id)->exists()) {
            return redirect()->back()->withErrors(['order' => 'El campo "order" ya existe en la tabla.']);
        }

        $denomination->update($validated);
        return redirect()->route('denominations.admin')->with('success', 'Denominación actualizada correctamente.');
    }

    public function destroy(Request $request)
    {
        $denomination = Denomination::find($request->input('id')); 
        if(!($denomination)){
            return redirect()->back()->withErrors(['order' => 'El elemento ya no existe.']);
        }

        $denomination->delete();
        return redirect()->route('denominations.admin')->with('success', 'Denominación eliminada correctamente.');
    }

    public function dataTable(Request $request)
    {
        $item = new Denomination();
        $items = $item->getDataTable($request);
        return response()->json($items);
    }

}

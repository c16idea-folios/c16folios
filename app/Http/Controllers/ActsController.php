<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Client;
use App\Models\Denomination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActsController extends Controller
{
    public function index()
    {
        
        
        return view('administrator.acts');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'order' => 'nullable|integer',
            'act' => 'nullable|string|max:255',
            'extract' => 'in:yes,no',
        ]);

        // Si el campo 'order' está vacío, obtener el número menor disponible
        if ($request->input('order') === null || $request->input('order')=="") {
            $validated['order'] = (Act::max('order') ?? 0) + 1; // Si no hay registros, asigna 1
        }

        // Verificar si el valor de 'order' ya existe
        if (Act::where('order', $validated['order'])->exists()) {
            return redirect()->back()->withErrors(['order' => 'El campo "order" ya existe en la tabla.']);
        }

   
        Act::create($validated);
        return redirect()->route('acts.admin')->with('success', 'Elemento creado correctamente.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'order' => 'nullable|integer',
            'act' => 'nullable|string|max:255',
            'extract' => 'in:yes,no',
        ]);

        $act = Act::find($request->input('id')); 
        if(!($act)){
            return redirect()->back()->withErrors(['order' => 'El elemento ya no existe.']);
        }
            // Si el campo 'order' está vacío, obtener el número menor disponible
            if ($request->input('order') === null || $request->input('order')=="") {
                $validated['order'] = (Act::max('order') ?? 0) + 1; // Si no hay registros, asigna 1
            }
    

        // Verificar si el valor de 'order' ya existe y no corresponde al registro actual
        if (Act::where('order', $validated['order'])->where('id', '!=', $act->id)->exists()) {
            return redirect()->back()->withErrors(['order' => 'El campo "order" ya existe en la tabla.']);
        }

        $act->update($validated);
        return redirect()->route('acts.admin')->with('success', 'Elemento actualizado correctamente.');
    }

    public function destroy(Request $request)
    {
        $act = Act::find($request->input('id')); 
        if(!($act)){
            return redirect()->back()->withErrors(['order' => 'El elemento ya no existe.']);
        }

        $act->delete();
        return redirect()->route('acts.admin')->with('success', 'Elemento eliminado correctamente.');
    }

    public function dataTable(Request $request)
    {
        $item = new Act();
        $items = $item->getDataTable($request);
        return response()->json($items);
    }

}

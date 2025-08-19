<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Client;
use App\Models\Denomination;
use App\Models\FileType;
use App\Models\WorkTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WorkTeamController extends Controller
{
    public function index()
    {
        
        
        return view('administrator.work_team');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'order' => 'nullable|integer',
            'team' => 'string|max:255',
            'identifier' => 'string|max:255',
        ]);

        // Si el campo 'order' está vacío, obtener el número menor disponible
        if ($request->input('order') === null || $request->input('order')=="") {
            $validated['order'] = (WorkTeam::max('order') ?? 0) + 1; // Si no hay registros, asigna 1
        }

        // Verificar si el valor de 'order' ya existe
        if (WorkTeam::where('order', $validated['order'])->exists()) {
            return redirect()->back()->withErrors(['order' => 'El campo "order" ya existe en la tabla.']);
        }

   
        WorkTeam::create($validated);
        return redirect()->route('work_team.admin')->with('success', 'Elemento creado correctamente.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'order' => 'nullable|integer',
            'team' => 'string|max:255',
            'identifier' => 'string|max:255',
        ]);

        $workTeam = WorkTeam::find($request->input('id')); 
        if(!($workTeam)){
            return redirect()->back()->withErrors(['order' => 'El elemento ya no existe.']);
        }
            // Si el campo 'order' está vacío, obtener el número menor disponible
            if ($request->input('order') === null || $request->input('order')=="") {
                $validated['order'] = (WorkTeam::max('order') ?? 0) + 1; // Si no hay registros, asigna 1
            }
    

        // Verificar si el valor de 'order' ya existe y no corresponde al registro actual
        if (WorkTeam::where('order', $validated['order'])->where('id', '!=', $workTeam->id)->exists()) {
            return redirect()->back()->withErrors(['order' => 'El campo "order" ya existe en la tabla.']);
        }

        $workTeam->update($validated);
        return redirect()->route('work_team.admin')->with('success', 'Elemento actualizado correctamente.');
    }

    public function destroy(Request $request)
    {
        $workTeam = WorkTeam::find($request->input('id')); 
        if(!($workTeam)){
            return redirect()->back()->withErrors(['order' => 'El elemento ya no existe.']);
        }

        $workTeam->delete();
        return redirect()->route('work_team.admin')->with('success', 'Elemento eliminado correctamente.');
    }

    public function dataTable(Request $request)
    {
        $item = new WorkTeam();
        $items = $item->getDataTable($request);
        return response()->json($items);
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Client;
use App\Models\Denomination;
use App\Models\FileType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileTypeController extends Controller
{
    public function index()
    {
        
        
        return view('administrator.file_type');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'order' => 'nullable|integer',
            'type' => 'string|max:255',
        ]);

        // Si el campo 'order' está vacío, obtener el número menor disponible
        if ($request->input('order') === null || $request->input('order')=="") {
            $validated['order'] = (FileType::max('order') ?? 0) + 1; // Si no hay registros, asigna 1
        }

        // Verificar si el valor de 'order' ya existe
        if (FileType::where('order', $validated['order'])->exists()) {
            return redirect()->back()->withErrors(['order' => 'El campo "order" ya existe en la tabla.']);
        }

   
        FileType::create($validated);
        return redirect()->route('file_type.admin')->with('success', 'Elemento creado correctamente.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'order' => 'nullable|integer',
            'type' => 'string|max:255',
        ]);

        $fileType = FileType::find($request->input('id')); 
        if(!($fileType)){
            return redirect()->back()->withErrors(['order' => 'El elemento ya no existe.']);
        }
            // Si el campo 'order' está vacío, obtener el número menor disponible
            if ($request->input('order') === null || $request->input('order')=="") {
                $validated['order'] = (FileType::max('order') ?? 0) + 1; // Si no hay registros, asigna 1
            }
    

        // Verificar si el valor de 'order' ya existe y no corresponde al registro actual
        if (FileType::where('order', $validated['order'])->where('id', '!=', $fileType->id)->exists()) {
            return redirect()->back()->withErrors(['order' => 'El campo "order" ya existe en la tabla.']);
        }

        $fileType->update($validated);
        return redirect()->route('file_type.admin')->with('success', 'Elemento actualizado correctamente.');
    }

    public function destroy(Request $request)
    {
        $fileType = FileType::find($request->input('id')); 
        if(!($fileType)){
            return redirect()->back()->withErrors(['order' => 'El elemento ya no existe.']);
        }

        $fileType->delete();
        return redirect()->route('file_type.admin')->with('success', 'Elemento eliminado correctamente.');
    }

    public function dataTable(Request $request)
    {
        $item = new FileType();
        $items = $item->getDataTable($request);
        return response()->json($items);
    }

}

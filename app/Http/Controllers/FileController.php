<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Client;
use App\Models\Denomination;
use App\Models\File;
use App\Models\FileType;
use App\Models\Instrument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index()
    {
        
        $file_types=FileType::get(["*"]);

        $instruments = new Instrument();
        $instruments = $instruments->getForSelect();
        return view('administrator.files', compact('file_types','instruments'));
    }


    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'file_upload' => 'required',
            'instrument_act_id' => 'required',
            'file_type_id' => 'required'
        ]);

 
        $file = $request->file('file_upload');

        // Obtén el nombre original del archivo
        $originalName = $file->getClientOriginalName();

        // Guarda el archivo con el nombre original en la carpeta 'files' dentro del disco 'public'
        $filePath = $file->storeAs('files', $originalName, 'public');

        // Agrega el path al array validado
        $validated['file_path'] = $filePath;

    

        // Crear el usuario
        $file =File::create($validated);
       
        $file->save();
       
        if ($request->has('from_files_view')) {
            return redirect()->route('file.admin')->with('success', 'Elemento creado correctamente.');

        }else{
            return redirect()->route('instrument.admin')->with('success', 'Elemento creado correctamente.');

        }
    }

    public function update(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'instrument_act_id' => 'required',
            'file_type_id' => 'required',
        ]);
    
        // Buscar el archivo a actualizar
        $file = File::findOrFail($request->id);
    
        // Si se sube un nuevo archivo, actualizamos el archivo
        if ($request->hasFile('file_upload')) {
            // Eliminar el archivo anterior si existe
            if ($file->file_path && Storage::exists('public/' . $file->file_path)) {
                Storage::delete('public/' . $file->file_path);
            }
    
            $newFile = $request->file('file_upload');
    
            // Obtén el nombre original del nuevo archivo
            $originalName = $newFile->getClientOriginalName();
    
            // Guarda el nuevo archivo con el nombre original en la carpeta 'files' dentro del disco 'public'
            $filePath = $newFile->storeAs('files', $originalName, 'public');
    
            // Actualizamos el 'file_path' con el nuevo archivo
            $validated['file_path'] = $filePath;
        } else {
            // Si no se sube un archivo, mantenemos el archivo existente
            $validated['file_path'] = $file->file_path;
        }
    
        // Actualizamos el registro en la base de datos
        $file->update($validated);
    
        // Redirigir con mensaje de éxito
        if ($request->has('from_files_view')) {
            return redirect()->route('file.admin')->with('success', 'Elemento creado correctamente.');

        }else{
            return redirect()->route('instrument.admin')->with('success', 'Elemento creado correctamente.');

        }
    }

    public function destroy(Request $request)
    {
        $element = File::find($request->input('id')); 
    
        if (!($element)) {
            return redirect()->back()->withErrors(['order' => 'El elemento ya no existe.']);
        }
    
        // Verificar si el archivo existe en el almacenamiento antes de eliminarlo
        if ($element->file_path && Storage::exists('public/' . $element->file_path)) {
            // Eliminar el archivo físico del almacenamiento
            Storage::delete('public/' . $element->file_path);
        }
    
        // Eliminar el registro en la base de datos
        $element->delete();
    

        if ($request->has('from_files_view')) {
            return redirect()->route('file.admin')->with('success', 'Elemento creado correctamente.');

        }else{
            return redirect()->route('instrument.admin')->with('success', 'Elemento creado correctamente.');

        }

    }

    public function dataTable(Request $request)
    {
        $instrument_id=null;
        $input = $request->input('instrument_id'); 
        if (is_null($input) || $input === '') {
            $instrument_id=null;
        }else{
            $instrument_id=$input;
        }
        $item = new File();
        $items = $item->getDataTable($request,$instrument_id);
        return response()->json($items);
    }

}

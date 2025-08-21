<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Appearer;
use App\Models\Client;
use App\Models\Denomination;
use App\Models\FileType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppearerController extends Controller
{


    public function store(Request $request)
    {
        $validated = $request->validate([
            'instrument_act_id' => 'required',
            'appearer' => 'required|exists:clients,id',
            'legal_representative' => 'nullable|string',
            'legend' => 'required|in:yes,no',
            'observations' => 'nullable|string',
        ]);

        $clientData = $request->appearer;
        list($clientId, $personType) = explode('|', $clientData);
    
 $validated["appearer"]=$clientId;
   
            Appearer::create($validated);
    

        return back()->with('success', 'Elemento creado correctamente.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'instrument_act_id' => 'required',
            'appearer' => 'required|exists:clients,id',
            'legal_representative' => 'nullable|string',
            'legend' => 'required|in:yes,no',
            'observations' => 'nullable|string',
        ]);
        $appearer = Appearer::find($request->input('id')); 

        $clientData = $request->appearer;
        list($clientId, $personType) = explode('|', $clientData);
    
         $validated["appearer"]=$clientId;

        $appearer->update($validated);
        return back()->with('success', 'Elemento actualizado correctamente.');
    }

    public function destroy(Request $request)
    {
        $element = Appearer::find($request->input('id')); 
        if(!($element)){
            return redirect()->back()->withErrors(['order' => 'El elemento ya no existe.']);
        }

        $element->delete();

        return back()->with('success', 'Elemento eliminado correctamente.');
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Client;
use App\Models\Instrument;
use App\Models\InstrumentAct;
use App\Models\User;
use App\Models\WorkTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class InstrumentActController extends Controller
{
   
    public function client()
{
    return $this->belongsTo(Client::class, 'client_id');
}

    public function index()
    {
        
       
        return view('administrator.instrument_act');
    }



    public function store(Request $request)
    {
        // Validar la solicitud
        $validated = $request->validate([
            'instrument_id' => 'required',
            'created_at' => 'required|date',
            'client_id' => 'required|exists:clients,id',
            'legal_representative' => 'nullable|string',
            'act_id' => 'required|exists:acts,id',
            'cost' => 'nullable|numeric',
            'invoice' => 'required|in:not_applicable,request,sent',
            'appearing_character' => 'nullable|string',
            'fact_recorded' => 'nullable|string',
            'formalization_type' => 'nullable|in:NA,ORDINARIA,EXTRAORDINARIA',
            'notified_person' => 'nullable|string',
            'notification_subject' => 'nullable|string',
            'document_ratified' => 'nullable|string',
            'formalization_contract' => 'nullable|in:NA,CONTRATO,CONVENIO',
            'of' => 'nullable|string',
            'mercantile_declarations' => 'nullable|string',
            'in_favor_of' => 'nullable|string',
        ]);

        $clientData = $request->client_id;

// Separar el valor en id y person_type
list($clientId, $personType) = explode('|', $clientData);


        // Crear un nuevo registro en la base de datos
        $instrumentAct = InstrumentAct::create([
            'instrument_id' => $request->instrument_id,
            'created_at' => $request->created_at,
            'client_id' => $clientId,
            'legal_representative' => $request->legal_representative,
            'act_id' => $request->act_id,
            'cost' => $request->cost,
            'invoice' => $request->invoice,
            'appearing_character' => $request->appearing_character,
            'fact_recorded' => $request->fact_recorded,
            'formalization_type' => $request->formalization_type,
            'notified_person' => $request->notified_person,
            'notification_subject' => $request->notification_subject,
            'document_ratified' => $request->document_ratified,
            'formalization_contract' => $request->formalization_contract,
            'of' => $request->of,
            'mercantile_declarations' => $request->mercantile_declarations,
            'in_favor_of' => $request->in_favor_of,
        ]);

        // Redirigir con mensaje de éxito
        return back()->with('success', 'Elemento agregado con éxito');
    }


    public function update(Request $request)
    {

        $instrument = InstrumentAct::find($request->id);

        // Validar los datos del formulario
        $validatedData = $request->validate([
            'created_at' => 'required|date',


            'client_id' => 'required|exists:clients,id',
            'legal_representative' => 'nullable|string|max:255',
            'act_id' => 'required|exists:acts,id',
            'cost' => 'nullable|numeric',
            'invoice' => 'required|in:not_applicable,request,sent',
            // Agrega aquí las validaciones para los campos dinámicos
            'appearing_character' => 'nullable|string|max:255',
            'fact_recorded' => 'nullable|string',
            'formalization_type' => 'nullable|in:NA,ORDINARIA,EXTRAORDINARIA',
            'notified_person' => 'nullable|string|max:255',
            'notification_subject' => 'nullable|string|max:255',
            'document_ratified' => 'nullable|string',
            'formalization_contract' => 'nullable|in:NA,CONTRATO,CONVENIO',
            'of' => 'nullable|string|max:255',
            'mercantile_declarations' => 'nullable|string',
            'in_favor_of' => 'nullable|string',
        ]);

        $clientData = $request->client_id;

        // Separar el valor en id y person_type
        list($clientId, $personType) = explode('|', $clientData);

        $validatedData["client_id"]=$clientId;
        // Actualizar el registro en la base de datos
        $instrument->update($validatedData);

        // Redirigir al usuario con un mensaje de éxito
        return back()->with('success', 'Elemento actualizado con éxito.');
    }

    public function destroy(Request $request)
    {
        $element = InstrumentAct::find($request->id);

        if (!$element) {
            return redirect()->back()->withErrors(['user' => 'El elemento ya no existe.']);
        }



        $element->delete();
        return back()->with('success', 'Elemento eliminado correctamente.');
    }

    public function dataTable(Request $request)
    {


        
        $item = new InstrumentAct();
        $items = $item->getDataTable($request,$request->input('instrument_id'));
        return response()->json($items);
    }

    public function dataTableIndex(Request $request)
    {


        
        $item = new InstrumentAct();
        $items = $item->getDataTableIndex($request);
        return response()->json($items);
    }
}

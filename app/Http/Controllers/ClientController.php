<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Denomination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function index()
    {
        $denominations = Denomination::orderBy('order', 'asc')->get();
        return view('administrator.clients', compact('denominations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rfc' => 'nullable',

            'person_type' => 'in:física,moral',
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'second_last_name' => 'nullable|string|max:255',
            'denomination_id' => 'exists:denominations,id',
            'legal_representative' => 'nullable|string|max:255',
            'phone_number' => 'nullable|digits_between:10,15',
            'email' => 'nullable|email',
            'country' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'n_exterior' => 'nullable|string|max:255',
            'suburb' => 'nullable|string|max:255',
            'municipality' => 'nullable|string|max:255',
            'entity' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:10',
            'observations' => 'nullable|string',
            'picture_upload' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('picture_upload')) {
            $validated['picture_path'] = $request->file('picture_upload')->store('clients', 'public');
        }

        $cliente = Client::create($validated);

        if ($request->ajax()) {
            $cliente->append('formatted_name');
            return response()->json([
                'success' => true,
                'client' => $cliente
            ]);
        }
        return redirect()->route('clients.admin')->with('success', 'Cliente creado correctamente.');
    }



    public function update(Request $request)
    {

        $validated = $request->validate([
            'rfc' => 'nullable',
            'person_type' => 'in:física,moral',
            'name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'second_last_name' => 'nullable|string|max:255',
            'denomination_id' => 'exists:denominations,id',
            'legal_representative' => 'nullable|string|max:255',
            'phone_number' => 'nullable|digits_between:10,15',
            'email' => 'nullable|email',
            'country' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'n_exterior' => 'nullable|string|max:255',
            'suburb' => 'nullable|string|max:255',
            'municipality' => 'nullable|string|max:255',
            'entity' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:10',
            'observations' => 'nullable|string',
            'picture_upload' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);
       $client = Client::find($request->input('id'));


        if ($request->hasFile('picture_upload')) {
            if ($client->picture_path) {
                Storage::disk('public')->delete($client->picture_path);
            }
            $validated['picture_path'] = $request->file('picture_upload')->store('clients', 'public');
        }

        $client->update($validated);
        return redirect()->route('clients.admin')->with('success', 'Cliente actualizado correctamente.');
    }


    public function destroy(Request $request)
    {
        $item = Client::find($request->input('id'));
        if(!($item)){
            return redirect()->back()->withErrors(['order' => 'El elemento ya no existe.']);
        }

        $item->delete();
        return redirect()->route('clients.admin')->with('success', 'Elemento eliminado correctamente.');
    }



    public function dataTable(Request $request)
    {
        $item = new Client();
        $items = $item->getDataTable($request);
        return response()->json($items);
    }
}

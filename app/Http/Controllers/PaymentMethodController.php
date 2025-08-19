<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Client;
use App\Models\Denomination;
use App\Models\FileType;
use App\Models\PaymentMethod;
use App\Models\WorkTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    public function index()
    {
        
        
        return view('administrator.payment_method');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'order' => 'nullable|integer',
            'method' => 'string|max:255',
            'acronym' => 'string|max:255',
        ]);

        // Si el campo 'order' está vacío, obtener el número menor disponible
        if ($request->input('order') === null || $request->input('order')=="") {
            $validated['order'] = (PaymentMethod::max('order') ?? 0) + 1; // Si no hay registros, asigna 1
        }

        // Verificar si el valor de 'order' ya existe
        if (PaymentMethod::where('order', $validated['order'])->exists()) {
            return redirect()->back()->withErrors(['order' => 'El campo "order" ya existe en la tabla.']);
        }

   
        PaymentMethod::create($validated);
        return redirect()->route('payment_method.admin')->with('success', 'Elemento creado correctamente.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'order' => 'nullable|integer',
            'method' => 'string|max:255',
            'acronym' => 'string|max:255',
        ]);

        $paymentMethod = PaymentMethod::find($request->input('id')); 
        if(!($paymentMethod)){
            return redirect()->back()->withErrors(['order' => 'El elemento ya no existe.']);
        }
            // Si el campo 'order' está vacío, obtener el número menor disponible
            if ($request->input('order') === null || $request->input('order')=="") {
                $validated['order'] = (PaymentMethod::max('order') ?? 0) + 1; // Si no hay registros, asigna 1
            }
    

        // Verificar si el valor de 'order' ya existe y no corresponde al registro actual
        if (PaymentMethod::where('order', $validated['order'])->where('id', '!=', $paymentMethod->id)->exists()) {
            return redirect()->back()->withErrors(['order' => 'El campo "order" ya existe en la tabla.']);
        }

        $paymentMethod->update($validated);
        return redirect()->route('payment_method.admin')->with('success', 'Elemento actualizado correctamente.');
    }

    public function destroy(Request $request)
    {
        $workTeam = PaymentMethod::find($request->input('id')); 
        if(!($workTeam)){
            return redirect()->back()->withErrors(['order' => 'El elemento ya no existe.']);
        }

        $workTeam->delete();
        return redirect()->route('payment_method.admin')->with('success', 'Elemento eliminado correctamente.');
    }

    public function dataTable(Request $request)
    {
        $item = new PaymentMethod();
        $items = $item->getDataTable($request);
        return response()->json($items);
    }

}

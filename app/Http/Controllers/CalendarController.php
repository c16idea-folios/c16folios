<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Calendar;
use App\Models\Client;
use App\Models\Denomination;
use App\Models\FileType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CalendarController extends Controller
{
    public function index()
    {
        
        
        return view('administrator.calendar');
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

 
   

    public function dataTable(Request $request)
    {
        $item = new Calendar();
        $items = $item->getDataTable($request);
        return response()->json($items);
    }



    /**
     * Actualiza el estado del holiday en la tabla calendar.
     */
    public function update(Request $request)
    {
   
            // Validar los datos recibidos
            $validatedData = $request->validate([
                'date' => 'required', // Asegúrate del formato de la fecha (d-M-Y)
                'holiday' => 'required|in:Sí,No', // Validar que sea "Sí" o "No"
            ]);
            $validatedData['date'] = $this->convertSpanishDateToCarbon($validatedData['date']);
            // Convertir la fecha al formato Y-m-d (compatible con MySQL)
            $formattedDate = \Carbon\Carbon::createFromFormat('d-M-Y', $validatedData['date'])->format('Y-m-d');

            // Buscar si ya existe un registro con esa fecha
            $calendar = Calendar::where('day', $formattedDate)->first();

            if ($calendar) {
                // Actualizar el valor del holiday si ya existe
                $calendar->holiday = $validatedData['holiday'];
                $calendar->save();
            } else {
                // Crear un nuevo registro si no existe
                Calendar::create([
                    'day' => $formattedDate,
                    'holiday' => $validatedData['holiday'],
                ]);
            }

            // Responder con éxito
            return response()->json([
                'success' => true,
                'message' => 'El estado del día festivo se actualizó correctamente.',
            ]);

    }

        
    function convertSpanishDateToCarbon($dateString, $format = 'd-M-Y') {
        $monthMap = [
            'ENE' => 'JAN',
            'FEB' => 'FEB',
            'MAR' => 'MAR',
            'ABR' => 'APR',
            'MAY' => 'MAY',
            'JUN' => 'JUN',
            'JUL' => 'JUL',
            'AGO' => 'AUG',
            'SEP' => 'SEP',
            'OCT' => 'OCT',
            'NOV' => 'NOV',
            'DIC' => 'DEC',
        ];
    
        // Reemplazar los meses en español por inglés
        foreach ($monthMap as $es => $en) {
            $dateString = str_replace($es, $en, $dateString);
        }
    
        // Convertir a Carbon
        return  $dateString;
    }


}

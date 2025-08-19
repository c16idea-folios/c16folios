<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Client;
use App\Models\Denomination;
use App\Models\File;
use App\Models\FileType;
use App\Models\Instrument;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\WorkTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        
        $payment_methods=PaymentMethod::get(["*"]);


        $instruments = new Instrument();
        $instruments = $instruments->getForSelect();
        return view('administrator.payments', compact('payment_methods','instruments'));
    }


    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'instrument_act_id' => 'required',
            'payment_date' => 'required',
            'received_from' => 'nullable|string|max:255',
            'amount_paid' => 'required|numeric|gt:0',
            'observations' => 'nullable|string',
                    'payment_method_id' => 'required',

            
        ]);

 
      
        // Crear el usuario
        $file =Payment::create($validated);
       
        $file->save();
       
        if ($request->has('from_payments_view')) {
            return redirect()->route('payment.admin')->with('success', 'Elemento creado correctamente.');

        }else{
            return redirect()->route('instrument.admin')->with('success', 'Elemento creado correctamente.');

        }
    }

    public function update(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'id' => 'required',
            'instrument_act_id' => 'required',
            'payment_date' => 'required',
            'received_from' => 'nullable|string|max:255',
            'amount_paid' => 'required|numeric|gt:0',
            'observations' => 'nullable|string',
                    'payment_method_id' => 'required',

            
        ]);

        $payment = Payment::find($request->input('id'));
        $payment->update($validated);
      
    
        if ($request->has('from_payments_view')) {
            return redirect()->route('payment.admin')->with('success', 'Elemento actualizado correctamente.');

        }else{
            return redirect()->route('instrument.admin')->with('success', 'Elemento actualizado correctamente.');

        }
    }

    public function destroy(Request $request)
    {
        $element = Payment::find($request->input('id')); 
    
        if (!($element)) {
            return redirect()->back()->withErrors(['order' => 'El elemento ya no existe.']);
        }
    
       
        // Eliminar el registro en la base de datos
        $element->delete();
    

        if ($request->has('from_payments_view')) {
            return redirect()->route('payment.admin')->with('success', 'Elemento eliminado correctamente.');

        }else{
            return redirect()->route('instrument.admin')->with('success', 'Elemento eliminado correctamente.');

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
        $item = new Payment();
        $items = [];
        if ($request->has('from_payments_view')) {

            $items = $item->getDataTable2($request);

        }else{
            $items = $item->getDataTable($request,$instrument_id);

        }
        return response()->json($items);
    }

    public function generatePDF($id){
        $payment = Payment::findOrFail($id); 

        $item = new Payment();
        $details = $item->generatePaymentDetails($payment);
        $pdf = Pdf::loadView('pdf.custom_document', $details);
        return $pdf->stream('report.pdf', [
            'Attachment' => false,  // Si deseas abrir en el navegador en lugar de descargarlo
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="report.pdf"'  // Aquí defines el nombre del archivo
        ]);

    }



    public function report()
    {
        return view('administrator.report_payments');


    }


    public function reportData(Request $request)
    {
        $acts = Act::get(["*"]);
        $datasets = [];


        foreach ($acts as $key => $act) {
            
            $tmpData=[];
            for ($month = 1; $month <= 12; $month++) {
                          
    $totalAmountPaid = DB::table('payment')
    ->join('instrument_act', 'payment.instrument_act_id', '=', 'instrument_act.id')
    ->where('instrument_act.act_id', $act->id)  // Filtra por el act_id
    ->whereYear('payment.payment_date', $request->year)  // Filtra por el año
    ->whereMonth('payment.payment_date', $month)  // Filtra por el mes
    ->sum('payment.amount_paid');  // Suma el total de amoun

    array_push($tmpData,    $totalAmountPaid );

            }
array_push($datasets, ["label"=>$act->act,"data"=>$tmpData,"backgroundColor"=>$this->generateDistinctColorFromText($act->act)]);
        }
   
////////////////////////////////////////////

     // Datos para "teams"
     $teams = WorkTeam::get(["*"]);
     $labelsWorkTeam = [];
     $valuesWorkTeam = [];
     $totalPaidTeams = DB::table('payment')
     ->join('instrument_act', 'payment.instrument_act_id', '=', 'instrument_act.id')
     ->join('instruments', 'instrument_act.instrument_id', '=', 'instruments.id')
     ->join('users', 'instruments.responsible_id', '=', 'users.id')
     ->whereYear('payment.payment_date', $request->year)  // Filtra por el año
     ->sum('payment.amount_paid'); 
 
     foreach ($teams as $team) {
         array_push($labelsWorkTeam, $team->team);
        

$totalAmountPaid = DB::table('payment')
->join('instrument_act', 'payment.instrument_act_id', '=', 'instrument_act.id')
->join('instruments', 'instrument_act.instrument_id', '=', 'instruments.id')
->join('users', 'instruments.responsible_id', '=', 'users.id')
->where('users.work_team_id', $team->id)  // Filtrar por work_team_id
->whereYear('payment.payment_date', $request->year)  // Filtra por el año
->sum('payment.amount_paid'); 
         
         $percent = $totalPaidTeams > 0 ? ($totalAmountPaid / $totalPaidTeams) * 100 : 0;
 
         // Formatear el número a formato abreviado
         $formattedCount = $this->formatNumber($totalAmountPaid);
 
         array_push($valuesWorkTeam, [
             'cant' => $formattedCount,
             'percent' => $percent
         ]);
     }
 
////////////////////////////////////////////


////////////////////////////////////////////


     $payment_methods = PaymentMethod::get(["*"]);
     $labelsPaymentMethod= [];
     $valuesPaymentMethod = [];
     $totalPaidPaymentMethod = DB::table('payment')
     ->join('instrument_act', 'payment.instrument_act_id', '=', 'instrument_act.id')
     ->join('instruments', 'instrument_act.instrument_id', '=', 'instruments.id')
     ->join('users', 'instruments.responsible_id', '=', 'users.id')
     ->whereYear('payment.payment_date', $request->year)  // Filtra por el año
     ->sum('payment.amount_paid'); 
 
     foreach ($payment_methods as $payment_method) {
         array_push($labelsPaymentMethod, $payment_method->method);
        

$totalAmountPaid = DB::table('payment')
->join('instrument_act', 'payment.instrument_act_id', '=', 'instrument_act.id')
->join('instruments', 'instrument_act.instrument_id', '=', 'instruments.id')
->join('users', 'instruments.responsible_id', '=', 'users.id')
->where('payment.payment_method_id', $payment_method->id)  
->whereYear('payment.payment_date', $request->year)  // Filtra por el año
->sum('payment.amount_paid'); 
         
         $percent = $totalPaidPaymentMethod > 0 ? ($totalAmountPaid / $totalPaidPaymentMethod) * 100 : 0;
 
         // Formatear el número a formato abreviado
         $formattedCount = $this->formatNumber($totalAmountPaid);
 
         array_push($valuesPaymentMethod, [
             'cant' => $formattedCount,
             'percent' => $percent
         ]);
     }
 
////////////////////////////////////////////



      
            $data = [
              "datasets" => $datasets,
              "labelsWorkTeam" => $labelsWorkTeam,
              "valuesWorkTeam" => $valuesWorkTeam,

              "labelsPaymentMethod" => $labelsPaymentMethod,
              "valuesPaymentMethod" => $valuesPaymentMethod,
            ];
  
        return response()->json($data);

    }


    function generateDistinctColorFromText($actText)
    {
        // Crear un hash del texto del act
        $hash = md5($actText);

        // Extraer valores del hash y asignarlos a los componentes RGB de forma más variada
        $r = hexdec(substr($hash, 0, 2)); // Extrae los primeros 2 caracteres
        $g = hexdec(substr($hash, 2, 2)); // Extrae los siguientes 2 caracteres
        $b = hexdec(substr($hash, 4, 2)); // Extrae los siguientes 2 caracteres

        // Asegurarse de que los colores sean más variados
        // Esto se hace manipulando los valores de RGB para forzar la dispersión entre colores

        // Ajuste para que los colores no se repitan tanto y cubran un espectro más amplio
        $r = ($r + 128) % 256; // Mueve el valor hacia el medio del espectro de rojo
        $g = ($g + 64) % 256;  // Mueve el valor hacia un rango más equilibrado de verde
        $b = ($b + 192) % 256; // Mueve el valor hacia el rango más alto para azul

        // Convertir los valores a formato hexadecimal
        $hexColor = sprintf("#%02x%02x%02x", $r, $g, $b);

        return $hexColor;
    }
    private function formatNumber($number)
    {
        if ($number >= 1000000) {
            return round($number / 1000000, 1) . 'M'; // Ejemplo: 1500000 -> 1.5M
        } elseif ($number >= 1000) {
            return round($number / 1000, 1) . 'k'; // Ejemplo: 2500 -> 2.5k
        } else {
            return $number; // Para números menores a 1000
        }
    }



    public function reportDataTable(Request $request)
    {
        $item = new Payment();
        $items = $item->getDataTableReport($request);
        return response()->json($items);
    }




}

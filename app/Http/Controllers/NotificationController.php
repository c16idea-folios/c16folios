<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Client;
use App\Models\Denomination;
use App\Models\File;
use App\Models\FileType;
use App\Models\Instrument;
use App\Models\NoticeType;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class NotificationController extends Controller
{
    public function index()
    {


        $act=Act::where('act','Constitución')->first();

        $notices_type=NoticeType::where('foreigners','no')->where('act_id',$act->id)->get(["*"]);
        $notices_type_foreigner=NoticeType::where('act_id',$act->id)->get(["*"]);


        $instruments = new Instrument();
        $instruments = $instruments->getForSelect();
        
        return view('administrator.notification', compact('notices_type','notices_type_foreigner','instruments'));
    }


    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'instrument_act_id' => 'required',
            'notice_type_id' => 'required',
            'presentation_date' => 'required',
            'observations' => 'nullable|string'
            
        ]);

 
      
        // Crear el usuario
        $file =Notification::create($validated);
       
        $file->save();
       
        if ($request->has('from_notifications_view')) {
            return redirect()->route('notification.admin')->with('success', 'Elemento creado correctamente.');

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
            'notice_type_id' => 'required',
            'presentation_date' => 'required',
            'observations' => 'nullable|string'

            
        ]);

        $payment = Notification::find($request->input('id'));
        $payment->update($validated);
      
    
        if ($request->has('from_notifications_view')) {
            return redirect()->route('notification.admin')->with('success', 'Elemento actualizado correctamente.');

        }else{
            return redirect()->route('instrument.admin')->with('success', 'Elemento actualizado correctamente.');

        }
    }

    public function destroy(Request $request)
    {
        $notification = Notification::find($request->input('id')); 
        $notification->status= 'Pendiente';
        $notification->save();


        if ($request->has('from_notifications_view')) {
            return redirect()->route('notification.admin')->with('success', 'Elemento procesado correctamente.');

        }else{
            return redirect()->route('instrument.admin')->with('success', 'Elemento procesado correctamente.');

        }
        /*
        $element = Notification::find($request->input('id')); 
    
        if (!($element)) {
            return redirect()->back()->withErrors(['order' => 'El elemento ya no existe.']);
        }
    
       
        // Eliminar el registro en la base de datos
        $element->delete();
    

        if ($request->has('from_notifications_view')) {
            return redirect()->route('notification.admin')->with('success', 'Elemento eliminado correctamente.');

        }else{
            return redirect()->route('instrument.admin')->with('success', 'Elemento eliminado correctamente.');

        }
*/
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
        $item = new Notification();
        $items = [];
        if ($request->has('from_notifications_view')) {

            $items = $item->getDataTable2($request);

        }else{
            $items = $item->getDataTable($request,$instrument_id);

        }
        return response()->json($items);
    }



}

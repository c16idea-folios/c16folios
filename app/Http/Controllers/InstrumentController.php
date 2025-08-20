<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Appearer;
use App\Models\Client;
use App\Models\Denomination;
use App\Models\FileType;
use App\Models\Instrument;
use App\Models\InstrumentAct;
use App\Models\NoticeType;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\WorkTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Services\ActService;  // Asegúrate de importar el servicio ActService
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class InstrumentController extends Controller
{
    public function index()
    {

        $users = [];
        array_push($users, auth()->user());
        $file_types = FileType::get(["*"]);
        $payment_methods = PaymentMethod::get(["*"]);

        $act = Act::where('act', 'Constitución')->first();

        $notices_type = NoticeType::where('foreigners', 'no')->where('act_id', $act->id)->get(["*"]);
        $notices_type_foreigner = NoticeType::where('act_id', $act->id)->get(["*"]);
        $minNo = Instrument::min('no'); // Obtiene el número menor
        $maxNo = Instrument::max('no'); // Obtiene el número mayor


        return view('administrator.instruments', compact('users', 'file_types', 'payment_methods', 'notices_type', 'notices_type_foreigner', 'minNo', 'maxNo'));
    }

    public function canceled()
    {



        return view('administrator.canceled');
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'responsible_id' => 'exists:users,id',
            'type' => 'required|in:Póliza,Acta',
            'authorization_date' => 'nullable|date',
            'created_at' => 'nullable|date',
        ]);


        // Si el campo 'order' está vacío, obtener el número menor disponible

        $validated['no'] =  (Instrument::max('no') ?? 0) + 1; // Si no hay registros, asigna 1




        // Crear el usuario
        $instrument = Instrument::create($validated);
        $instrument->created_at = $validated['created_at'] ?? now();
        $instrument->save();


        return redirect()->route('instrument.admin.edit', ['id' => $instrument->id])->with('success', 'Elemento creado correctamente.');
    }




    public function update(Request $request)
    {
        $validated = $request->validate([
            'no' => 'nullable|integer',
            'responsible_id' => 'exists:users,id',
            'type' => 'required|in:Póliza,Acta',
            'authorization_date' => 'nullable|date',
            'created_at' => 'nullable|date',
        ]);

        $instrument = Instrument::find($request->input('instrument_id'));
        if (!($instrument)) {
            return redirect()->back()->withErrors(['no' => 'El elemento ya no existe.']);
        }
        // Si el campo 'order' está vacío, obtener el número menor disponible
        if ($request->input('no') === null || $request->input('no') == "") {
            $validated['no'] = (Instrument::max('no') ?? 0) + 1; // Si no hay registros, asigna 1
        }


        // Verificar si el valor de 'order' ya existe y no corresponde al registro actual
        if (Instrument::where('no', $validated['no'])->where('id', '!=', $instrument->id)->exists()) {
            return redirect()->back()->withErrors(['no' => 'El campo "no" ya existe en la tabla.']);
        }

        $instrument->update($validated);
        return redirect()->route('instrument.admin.edit', ['id' => $instrument->id])->with('success', 'Elemento actualizado correctamente.');
    }
    public function updateSubmission(Request $request)
    {

        $instrument = Instrument::find($request->input('instrument_id'));
        if (!($instrument)) {
            return redirect()->back()->withErrors(['no' => 'El elemento ya no existe.']);
        }

        $instrument->submission_date = $request->submission_date;
        $instrument->who_receives = $request->who_receives;


        $instrument->save();
        return redirect()->route('instrument.admin')->with('success', 'Elemento actualizado correctamente.');
    }


    public function edit($id)
    {
        $users = [];


        // Buscar el instrumento por su ID, ejemplo:
        $instrument = Instrument::findOrFail($id);
        $instrument["created_at_f"] = $instrument->created_at ? $instrument->created_at->format('Y-m-d') : null;

        // Obtener todos los clientes junto con su denominación (usando una relación)
        $clients = Client::with('denomination')->get();
        $acts = Act::get(["*"]);

        $user = User::where('id', $instrument->responsible_id)->first();

        if (  $user && ((int) $user->id === (int) auth()->user()->id)) {
            array_push($users, auth()->user());
        } else {

            if($user){
                array_push($users, $user);

            }

            array_push($users, auth()->user());
        }


        $acts_table = InstrumentAct::where("instrument_id", $id)->get(["*"]);

        $instrument_acts = InstrumentAct::with(['act', 'client.denomination'])
            ->get()
            ->map(function ($item) {
                $actName = $item->act ? $item->act->act : '';

                $clientName = '';
                if ($item->client) {
                    if ($item->client->person_type == 'moral') {
                        $denomination = $item->client->denomination;
                        if ($denomination) {
                            $clientName = $item->client->name . ' ' . $denomination->acronym;
                        }
                    } elseif ($item->client->person_type == 'física') {
                        $clientName = $item->client->name . ' ' . $item->client->last_name . ' ' . $item->client->second_last_name;
                    }
                }

                $item["act_client_name"] = trim($actName . ' ' . $clientName);
                return $item;
            });


        // Obtén los IDs de los instrument_act relacionados con el instrument_id dado
        $instrumentActIds = InstrumentAct::where('instrument_id', $id)->pluck('id');

        // Obtén los appearers relacionados con los instrument_act_ids obtenidos
        $appearers = Appearer::whereIn('instrument_act_id', $instrumentActIds)->get();

        // Obtener las denominaciones, para modal crear cliente
        $denominations = Denomination::orderBy('order', 'asc')->get();


        return view('administrator.instrument_edit', compact('instrument', 'users', 'clients', 'acts', 'acts_table', 'instrument_acts', 'appearers', 'denominations'));
    }


    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->id);


        $user['password'] = bcrypt($user['username']);
        $user->save();


        return redirect()->route('user.admin')->with('success',   "La contraseña se ha restablecido exitosamente para <br> Nombre: " . $user->name . " " . $user->last_name . " " . $user->second_last_name . "<br>" . "Usuario: " . $user->username . "<br>" . "Nueva contraseña: " . $user->username . "<br>" . "El cambio se aplicara a partir del próximo inicio de sesión.");
    }



    public function destroy(Request $request)
    {


        $notification = Instrument::find($request->input('id'));
        $notification->status = 'canceled';
        $notification->save();



        return redirect()->route('instrument.admin')->with('success', 'Elemento procesado correctamente.');


        /*
        $element = Instrument::find($request->id);

        if (!$element) {
            return redirect()->back()->withErrors(['user' => 'El elemento ya no existe.']);
        }



        $element->delete();

        return redirect()->route('instrument.admin')->with('success', 'Elemento eliminado correctamente.');*/
    }

    public function dataTable(Request $request)
    {
        $item = new Instrument();

        // Verifica si el parámetro 'estatus' existe en la solicitud
        if ($request->has('status')) {
            $status = $request->input('status');
            $items = $item->getDataTable($request, $status);
        } else {
            // Si no existe, llama al método sin el parámetro 'estatus'
            $items = $item->getDataTable($request);
        }

        return response()->json($items);
    }

    public function report()
    {
        // Datos para "acts"
        $acts = Act::get(["*"]);
        $labels = [];
        $values = [];
        $totalCountActs = InstrumentAct::count();

        foreach ($acts as $act) {
            array_push($labels, $act->act);
            $count = InstrumentAct::where('act_id', $act->id)->count();
            $percent = $totalCountActs > 0 ? ($count / $totalCountActs) * 100 : 0;

            // Formatear el número a formato abreviado
            $formattedCount = $this->formatNumber($count);

            array_push($values, [
                'count' => $formattedCount,
                'percent' => $percent
            ]);
        }

        // Datos para "users"
        $users = User::get(["*"]);
        $labels2 = [];
        $values2 = [];
        $totalCountUsers = Instrument::count();

        foreach ($users as $user) {
            array_push($labels2, $user->name);
            $count = Instrument::where('responsible_id', $user->id)->count();
            $percent = $totalCountUsers > 0 ? ($count / $totalCountUsers) * 100 : 0;

            // Formatear el número a formato abreviado
            $formattedCount = $this->formatNumber($count);

            array_push($values2, [
                'count' => $formattedCount,
                'percent' => $percent
            ]);
        }

        // Datos para "teams"
        $teams = WorkTeam::get(["*"]);
        $labels3 = [];
        $values3 = [];
        $totalCountTeams = WorkTeam::count();

        foreach ($teams as $team) {
            array_push($labels3, $team->team);
            $count = Instrument::whereHas('responsible', function ($query) use ($team) {
                $query->where('work_team_id', $team->id);
            })->count();

            $percent = $totalCountTeams > 0 ? ($count / $totalCountTeams) * 100 : 0;

            // Formatear el número a formato abreviado
            $formattedCount = $this->formatNumber($count);

            array_push($values3, [
                'count' => $formattedCount,
                'percent' => $percent
            ]);
        }

        // Enviar todos los datos a la vista
        $data = [
            'labels' => $labels,
            'values' => $values,
            'labels2' => $labels2,
            'values2' => $values2,
            'labels3' => $labels3,
            'values3' => $values3
        ];

        return view('administrator.report_instruments', compact('data'));
    }


    // Nueva función para formatear números
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


    public function exportExtracts($min, $max, $format)
    {
        // Validar el formato
        if (!in_array(mb_strtoupper($format), ['PDF', 'WORD'])) {
            return response()->json(['error' => 'Formato inválido. Use "pdf" o "word".'], 400);
        }

        // Obtener los datos
        $instrumentActs = Instrument::whereBetween('no', [$min, $max])
            ->with('acts')
            ->get()
            ->pluck('acts')
            ->flatten();

        $extracts = [];
        foreach ($instrumentActs as $instrumentAct) {
            $extract = ActService::getFormatExtract($instrumentAct);
            array_push($extracts, $extract);
        }
        $format=mb_strtoupper($format, 'UTF-8');
        // Generar PDF
        if ($format === 'PDF') {
            $pdf = Pdf::loadView('pdf.extracts', ['extracts' => $extracts]);
            return $pdf->stream('extractos.pdf', [
                'Attachment' => false,
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="extractos.pdf"'
            ]);
        }

        // Generar Word

    if ($format === 'WORD') {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection([
            'marginLeft'   => 1200,
            'marginRight'  => 1200,
            'marginTop'    => 1200,
            'marginBottom' => 1200,
        ]);

        $style = [
            'name' => 'Arial',
            'size' => 12,
            'bold' => false,
            'allCaps' => true,  // Convierte a mayúsculas
        ];

        foreach ($extracts as $extract) {
            $textRun = $section->addTextRun(['alignment' => 'both']); // Justificado
            $textRun->addText($extract, $style);
            $section->addTextBreak(1);
        }

        $filePath = storage_path('app/public/extractos.docx');
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
    }
}

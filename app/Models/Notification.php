<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use NumberFormatter;
use Carbon\Carbon;

class Notification extends Model
{
    protected $table='notification';
    protected $guarded= ['id'];



    public function getDataTable(Request $request, $instrument_id = null)
    {

   


        $columns = array(
            0 => 'id',
            1 => 'no',
            2 => 'act',
            3 => 'client',
            4 => 'presentation_date_f',
            5 => 'notice_type_text',
            6 => 'observations'
        );
    
        $query = Notification::query();
    
        // Si el $instrument_id no es null, filtrar por el instrument_id
        if ($instrument_id !== null) {
            $query->select('notification.*')->join('instrument_act', 'instrument_act.id', '=', 'notification.instrument_act_id')
                  ->join('acts', 'acts.id', '=', 'instrument_act.act_id')
                  ->where('instrument_act.instrument_id', $instrument_id); // Filtrar por instrument_id
        }
    
        $totalData = $query->count();
        $totalFiltered = $totalData;
    
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;
    
        $items = [];
        if (empty($request->input('search.value'))) {
            if ($limit == -1) {
                $items = $query->get(['*'])->map(function ($item) {
                        return $this->mapDataTable($item);
                    })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $items = $query->get(['*'])->map(function ($item) {
                        return $this->mapDataTable($item);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)
                    ->values()->all();
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $items = $query->get(['*'])->map(function ($item) {
                        return $this->mapDataTable($item);
                    })
                    ->filter(function ($item) use ($search, $columns, $request) {
                        return $this->filterSearch($item, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $items = $query->get(['*'])->map(function ($item) {
                        return $this->mapDataTable($item);
                    })
                    ->filter(function ($item) use ($search, $columns, $request) {
                        return $this->filterSearch($item, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)
                    ->values()->all();
            }
    
            $totalFiltered = $query->get(['*'])->map(function ($item) {
                return $this->mapDataTable($item);
            })->filter(function ($item) use ($search, $columns, $request) {
                    return $this->filterSearch($item, $search, $columns, $request);
                })
                ->count();
        }
    
        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $items
        ];
    
        return $result;
    }






    public function getDataTable2(Request $request, $instrument_id = null)
    {
        $columns = array(
            0 => 'id',
            1 => 'no',
            2 => 'client',
            3 => 'act',
            4 => 'created_at_act',
            5 => 'notice_type_text',
            6 => 'days',
            7 => 'expiration_date',
            8 => 'days_remaining',
            9 => 'presentation_date',
            10 => 'authorization_date',
        );
    
        $query = Notification::query();
    
        // Si el $instrument_id no es null, filtrar por el instrument_id
        if ($instrument_id !== null) {
            $query->select('notification.*')
                  ->join('instrument_act', 'instrument_act.id', '=', 'notification.instrument_act_id')
                  ->join('acts', 'acts.id', '=', 'instrument_act.act_id')
                  ->where('instrument_act.instrument_id', $instrument_id);
        }
    
        $totalData = $query->count();
        $totalFiltered = $totalData;
    
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;
    
        $status = $request->input('status'); // Recoge el status del request
        $items = [];
    
        if (empty($request->input('search.value'))) {
            // Obtener todos los datos sin búsqueda
            $items = $query->get()->map(function ($item) use ($status) {
                $mappedItem = $this->mapDataTable2($item);
                if ($status == "Presentado" && $item["status"] == "Presentado") {
                    return $mappedItem;
                } elseif ($status == "Pendiente" && $item["status"] == "Pendiente") {
                    return $mappedItem;
                } elseif ($status == "Todos") {
                    return $mappedItem;
                } else {
                    return null;
                }
            }); // Eliminar valores nulos y reiniciar índices
    
            $items = $items->filter()->values();
            // Aplicar ordenamiento
            $items = $items->sortBy(function ($item) use ($order) {
                return $item[$order];  // Ordenar por la columna seleccionada
            }, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values();
    
            // Aplicar paginación
            if ($limit != -1) {
                $items = $items->slice($start, $limit)->values();
            }
    
            $totalFiltered = $items->count(); // Recalcular el total de elementos filtrados
        } else {
            // Si hay búsqueda
            $search = $request->input('search.value');
    
            $items = $query->get()->map(function ($item) use ($status) {
                $mappedItem = $this->mapDataTable2($item);
    
                if ($status == "Presentado" && $item["status"] == "Presentado") {
                    return $mappedItem;
                } elseif ($status == "Pendiente" && $item["status"] == "Pendiente") {
                    return $mappedItem;
                } elseif ($status == "Todos") {
                    return $mappedItem;
                } else {
                    return null;
                }
            });
            $items = $items->filter()->values();

            $items = $items->filter(function ($item) use ($search, $columns, $request) {
                return $this->filterSearch($item, $search, $columns, $request);
            });
    
    
            // Aplicar ordenamiento
            $items = $items->sortBy(function ($item) use ($order) {
                return $item[$order];  // Ordenar por la columna seleccionada
            }, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values();
    
            // Aplicar paginación
            if ($limit != -1) {
                $items = $items->slice($start, $limit)->values();
            }
    
            $totalFiltered = $items->count(); // Recalcular el total de elementos filtrados
        }
    
        $result = [
            'iTotalRecords'        => $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               => $items
        ];
    
        return $result;
    }
    
    function mapDataTable2($item)
    {
     

   

        $noticeType = NoticeType::where('id',$item['notice_type_id'])->get(["*"])[0];

    $item["notice_type_text"]= $noticeType->type;
    $item["days"]= $noticeType->days;

        // Obtén el registro de la tabla 'instrument_act' a través del 'instrument_act_id' de la tabla 'file'
    $instrumentActTmp = InstrumentAct::where('id',$item['instrument_act_id'])->get(["*"])[0];



    //actos para editar

    $instrumentActs = InstrumentAct::with(['act', 'client'])
    ->where('instrument_id', $instrumentActTmp->instrument_id)
    ->get(); 

    $acts_list=[];
    foreach ($instrumentActs as $instrumentAct) {


        // Generar HTML para los 'clients'
        $client = $instrumentAct->client;

        $isForeigner = false;
        if(mb_strtolower($client->country,'UTF-8' )!=mb_strtolower("méxico",'UTF-8')){
            $isForeigner=true;
        }else{
            $isForeigner=false;
        }

        if ($client) {
            $clientName = $client->name;
            if ($client->person_type == "moral") {
                
              
                array_push($acts_list,["text"=> strtoupper($client["name"])." (".$instrumentAct->act->act.")", "id"=> $instrumentAct->id,"is_foreigner"=> $isForeigner, "show_in_notification"=>(($instrumentAct->act->act=="Constitución")?true:false)] );

            } else if ($client['person_type'] == "física") {
                $clientName = $client["name"] . " " . $client["last_name"] . " " . $client["second_last_name"];
                array_push($acts_list, ["text"=>strtoupper($clientName)." (".$instrumentAct->act->act.")","id"=>$instrumentAct->id,"is_foreigner"=> $isForeigner, "show_in_notification"=>(($instrumentAct->act->act=="Constitución")?true:false) ]);
            } else {
             
            }

           
        }

       
    }
        $item["acts_list"]=$acts_list;

    //actos para editar


    // 1. Obtener el campo 'act' desde la relación con la tabla 'acts'
    $act = Act::find($instrumentActTmp->act_id);
    $item['act'] = $act ? $act->act : "";
    $item['created_at_act'] =   Carbon::parse($instrumentActTmp->created_at)->format('Y-m-d');


    $item['expiration_date'] =  $this->addBusinessDays(Carbon::parse($instrumentActTmp->created_at),$item["days"], []);
    $item['days_remaining'] =  $this->getWorkingDaysDifference($item['expiration_date'], []);
    $item['expiration_date'] = Carbon::parse($item['expiration_date'] )->format('Y-m-d');
    

    

    // 2. Obtener el campo 'no' desde la relación con la tabla 'instruments'
    $instrument = Instrument::find($instrumentActTmp->instrument_id);
    $item['no'] = $instrument ? $instrument->no : "";

    // 3. Obtener el cliente desde la relación con la tabla 'clients' y formatear el nombre
    $client = Client::find($instrumentActTmp->client_id);
    if ($client) {
        if ($client['person_type'] == "moral") {
          
            $item['client'] = $client['name'];
        } elseif ($client['person_type'] == "física") {
            $item['client'] = $client['name'] . " " . $client['last_name'] . " " . $client['second_last_name'];
        } else {
            $item['client'] = "";
        }
    } else {
        $item['client'] = "";
    }


    $item['observations'] = $item->observations?$item->observations:"";

    $item['client'] =  strtoupper($item['client']);
    $item["payment_date_f"]=$item->payment_date;

    $item["authorization_date"]=$instrument->authorization_date;
    
 


        return   $item;
    }

    function addBusinessDays(Carbon $startDate, int $businessDays, array $holidays = []): Carbon
{
    $currentDate = $startDate->copy();
    $holidaysSet = array_flip($holidays); // Optional holidays converted to a set for fast lookups

    while ($businessDays > 0) {
        $currentDate->addDay(); // Move to the next day

        // Check if the current day is a weekday and not a holiday
        if (!$currentDate->isWeekend() && !isset($holidaysSet[$currentDate->toDateString()])) {
            $businessDays--;
        }
    }

    return $currentDate;
}

function getWorkingDaysDifference(Carbon $date2, array $holidays = []): int
{

    $currentDate = Carbon::now('America/Mexico_City');

    // Ensure date1 is always the earlier date
    $startDate = $currentDate->copy();
    $endDate = $date2->copy();

    if ($startDate->greaterThan($endDate)) {
        [$startDate, $endDate] = [$endDate, $startDate];
    }

    $holidaysSet = array_flip($holidays); // Convert holidays to a set for fast lookups
    $workingDays = 0;

    // Iterate through each day in the range
    while ($startDate->lessThan($endDate)) {
        $startDate->addDay();

        // Check if the current day is a weekday and not a holiday
        if (!$startDate->isWeekend() && !isset($holidaysSet[$startDate->toDateString()])) {
            $workingDays++;
        }
    }

    return $workingDays;
}

    

    function mapDataTable($item)
    {



     
        // Obtén el registro de la tabla 'instrument_act' a través del 'instrument_act_id' de la tabla 'file'
    $instrumentActTmp = InstrumentAct::where('id',$item['instrument_act_id'])->get(["*"])[0];



    //actos para editar

    $instrumentActs = InstrumentAct::with(['act', 'client'])
    ->where('instrument_id', $instrumentActTmp->instrument_id)
    ->get(); 

    $acts_list=[];
    foreach ($instrumentActs as $instrumentAct) {


        // Generar HTML para los 'clients'
        $client = $instrumentAct->client;
        if ($client) {
            $clientName = $client->name;
            if ($client->person_type == "moral") {
  
                array_push($acts_list,["text"=> strtoupper($client["name"])." (".$instrumentAct->act->act.")", "id"=> $instrumentAct->id] );
            } else if ($client['person_type'] == "física") {
                $clientName = $client["name"] . " " . $client["last_name"] . " " . $client["second_last_name"];
                array_push($acts_list, ["text"=>strtoupper($clientName)." (".$instrumentAct->act->act.")","id"=>$instrumentAct->id ]);
            } else {
             
            }

           
        }

       
    }
        $item["acts_list"]=$acts_list;

    //actos para editar


    // 1. Obtener el campo 'act' desde la relación con la tabla 'acts'
    $act = Act::find($instrumentActTmp->act_id);
    $item['act'] = $act ? $act->act : "";

    // 2. Obtener el campo 'no' desde la relación con la tabla 'instruments'
    $instrument = Instrument::find($instrumentActTmp->instrument_id);
    $item['no'] = $instrument ? $instrument->no : "";

    // 3. Obtener el cliente desde la relación con la tabla 'clients' y formatear el nombre
    $client = Client::find($instrumentActTmp->client_id);
    if ($client) {
        if ($client['person_type'] == "moral") {
          
            $item['client'] = $client['name'];
        } elseif ($client['person_type'] == "física") {
            $item['client'] = $client['name'] . " " . $client['last_name'] . " " . $client['second_last_name'];
        } else {
            $item['client'] = "";
        }
    } else {
        $item['client'] = "";
    }


    $item['observations'] = $item->observations?$item->observations:"";

    $item['client'] =  strtoupper($item['client']);
    $item["presentation_date_f"]=$item->presentation_date;

    $notice_type = NoticeType::where('id',$item['notice_type_id'])->get(["*"])[0];

    $item["notice_type_text"]=  $notice_type->type;

    



        return   $item;
    }




    function filterSearch($obj, $search, $columns, $request)
    {
        $item = false;
            //general
            foreach ($columns as $colum)
                if (stristr(($obj[$colum]), $search))
                    $item = $obj;
            return $item;
    }





}

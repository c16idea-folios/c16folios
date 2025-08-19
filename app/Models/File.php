<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class File extends Model
{
    protected $table='file';
    protected $guarded= ['id'];


    public function getDataTable(Request $request, $instrument_id = null)
    {
        $columns = array(
            0 => 'id',
            1 => 'no',
            2 => 'act',
            3 => 'client',
            4 => 'type',
            5 => 'name_file',
            6 => 'updated_at_f',
            7 => 'file_path',
        );
    
        $query = File::query();
    
        // Si el $instrument_id no es null, filtrar por el instrument_id
        if ($instrument_id !== null) {
            $query->select('file.*')->join('instrument_act', 'instrument_act.id', '=', 'file.instrument_act_id')
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
    

    function mapDataTable($item)
    {
/*
        0 => 'id',
        1 => 'no',
        2 => 'act',
        3 => 'client',
        4 => 'name_file',
        5 => 'update_at_f',
        6 => 'file_path',

        */



     
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

    $item['client'] =  strtoupper($item['client']);
    $item["updated_at_f"]=$item->updated_at ? $item->updated_at->format('Y-m-d h:iA') : "";

    $item['name_file'] = str_replace('files/', '', $item['file_path']);

    $fileType = FileType::find($item->file_type_id);
    $item['type'] = $fileType ? $fileType->type : "";

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

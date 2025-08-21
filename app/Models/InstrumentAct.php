<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class InstrumentAct extends Model
{
    protected $table='instrument_act';
    protected $guarded= ['id'];

    protected $fillable =[
        'id',
        'instrument_id',
        'client_id',
        'legal_representative',
        'act_id',
        'cost',
        'invoice',
        'created_at',
        'updated_at',
        'appearing_character',
        'fact_recorded',
        'formalization_type',
        'notified_person',
        'notification_subject',
        'document_ratified',
        'formalization_contract',
        'of',
        'mercantile_declarations',
        'in_favor_of',
    ];

    /**
     * Obtiene el nombre del acto y cliente asociado al acto.
     */
    public function getActAndClientAttribute()
    {
        return $this->act->name . ' - ' . $this->client->formatted_name;
    }

     // Relación con el modelo Act
     public function act()
     {
         return $this->belongsTo(Act::class);
     }
 
     // Relación con el modelo Client
     public function client()
     {
         return $this->belongsTo(Client::class);
     }

     /**
      * Comparecientes al acto
      */
     public function appearers()
     {
         return $this->hasMany(Appearer::class);
     }

     public function payments()
     {
         return $this->hasMany(Payment::class, 'instrument_act_id');
     }

     public function instrument()
{
    return $this->belongsTo(Instrument::class, 'instrument_id');
}

    public function getDataTable(Request $request,$instrument_id)
    {


        

        $columns = array(
            0 => 'id',
            1 => 'created_at_f',
            2 => 'act',
            3 => 'client',
            4 => 'legal_representative',
            5 => 'cost',
            6 => 'invoice_text'
        );

        $totalData = InstrumentAct::where('instrument_id',$instrument_id)->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;


        $items = [];
        if (empty($request->input('search.value'))) {

            if ($limit == -1) {
                $items = InstrumentAct::where('instrument_id',$instrument_id)->get(['*'])->map(function ($item) {
                        return $this->mapDataTable($item);
                    })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $items = InstrumentAct::where('instrument_id',$instrument_id)->get(['*'])->map(function ($item) {
                        return $this->mapDataTable($item);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)
                    ->values()->all();
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $items =  InstrumentAct::where('instrument_id',$instrument_id)->get(['*'])->map(function ($item) {
                        return $this->mapDataTable($item);
                    })
                    ->filter(function ($item) use ($search, $columns, $request) {
                        return $this->filterSearch($item, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {

                $items =  InstrumentAct::where('instrument_id',$instrument_id)->get(['*'])->map(function ($item) {
                        return $this->mapDataTable($item);
                    })
                    ->filter(function ($item) use ($search, $columns, $request) {
                        return $this->filterSearch($item, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)
                    ->values()->all();
            }

            $totalFiltered = InstrumentAct::where('instrument_id',$instrument_id)->get(['*'])->map(function ($item) {
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




    public function getDataTableIndex(Request $request)
    {
        // Definimos las columnas
        $columns = array(
            0 => 'no',
            1 => 'created_at_f',
            2 => 'act_title',
            3 => 'client_name',
          
        );
    
        // Construimos la consulta base que no cambia
        $query = InstrumentAct::with('client', 'act') // Traemos la relación con 'client' y 'act'
            ->join('instruments', 'instrument_act.instrument_id', '=', 'instruments.id')
            ->join('acts', 'instrument_act.act_id', '=', 'acts.id') // Relacionamos con 'acts' usando 'act_id'
            ->where('instruments.status', 'active')
            ->select('instrument_act.*', 'instruments.no','instruments.responsible_id', 'acts.act as act'); // Seleccionamos 'no' de instruments y 'act' de acts
    
        // Total de registros sin filtros
        $totalData = $query->count();
        $totalFiltered = $totalData;
    
        // Obtención de parámetros de la paginación
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;
    
        // Si no hay búsqueda
        if (empty($request->input('search.value'))) {
    
            if ($limit == -1) {
                // Traemos los registros sin filtro de búsqueda
                $items = $query->get()
                    ->map(function ($item) {
                        return $this->mapDataTableIndex($item);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->values()
                    ->all();
            } else {
                // Traemos los registros con paginación
                $items = $query->get()
                    ->map(function ($item) {
                        return $this->mapDataTableIndex($item);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)
                    ->take($limit)
                    ->values()
                    ->all();
            }
        } else {
            // Si hay búsqueda, filtrar por los campos específicos
            $search = $request->input('search.value');
            if ($limit == -1) {
                // Traemos los registros sin filtro de búsqueda y con los filtros aplicados
                $items = $query->get()
                    ->map(function ($item) {
                        return $this->mapDataTableIndex($item);
                    })
                    ->filter(function ($item) use ($search, $columns, $request) {
                        return $this->filterSearch($item, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->values()
                    ->all();
            } else {
                // Traemos los registros con búsqueda y paginación
                $items = $query->get()
                    ->map(function ($item) {
                        return $this->mapDataTableIndex($item);
                    })
                    ->filter(function ($item) use ($search, $columns, $request) {
                        return $this->filterSearch($item, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)
                    ->take($limit)
                    ->values()
                    ->all();
            }
    
            // Total de registros filtrados con la búsqueda
            $totalFiltered = $query->get()
                ->map(function ($item) {
                    return $this->mapDataTableIndex($item);
                })
                ->filter(function ($item) use ($search, $columns, $request) {
                    return $this->filterSearch($item, $search, $columns, $request);
                })
                ->count();
        }
    
        // Devolvemos el resultado
        $result = [
            'iTotalRecords'        =>  $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               =>  $items
        ];
    
        return $result;
    }
    
    function mapDataTableIndex($item)
    {


        $color = "#000000";
        $user = User::where('id', $item->responsible_id)->first();
    
        if ($user && $user->work_team_id != null) {
            $work_team = WorkTeam::where('id', $user->work_team_id)->first();
            $color = $work_team->identifier;
        }
    

      $client=$item->client;
      $name="";
        if( $client['person_type']=="moral"){
            $denomination = Denomination::find($client['denomination_id']); 
            if( $denomination){
               $name=$client["name"]." ".$denomination->acronym;
            }
        
           }else if( $client['person_type']=="física"){
            $name=$client["name"]." ".$client["last_name"]." ".$client["second_last_name"];
           }else{
            $name="";
           }

           
        $item["client_name"]=strtoupper($name);

        $item["created_at_f"]=$item->created_at ? $item->created_at->format('Y-m-d') : "";

        $item["act_title"]="<p style='color:".$color."';>".$item->act."</p>";
        $item["act_title_simple"]=$item->act;
    
        return   $item;
    }

    function mapDataTable($item)
    {

        $act = Act::find($item['act_id']); 
  
           $item["act"]=$act->act;
           $client = Client::find($item['client_id']); 
           $item["person_type"]=$client["person_type"];
           if( $client['person_type']=="moral"){
            $denomination = Denomination::find($client['denomination_id']); 
            if( $denomination){
               $item["client"]=$client["name"]." ".$denomination->acronym;
            }
        
           }else if( $client['person_type']=="física"){
            $item["client"]=$client["name"]." ".$client["last_name"]." ".$client["second_last_name"];
           }else{
               $item["client"]="";
           }


           if($item->invoice=="not_applicable"){
            $item["invoice_text"]="No aplica";
           }else if($item->invoice=="request"){
            $item["invoice_text"]="Solicitar";

           }else if($item->invoice=="sent"){
            $item["invoice_text"]="Enviada";

           }else{
            $item["invoice_text"]="";

           }

           $item["created_at_f"]=$item->created_at ? $item->created_at->format('Y-m-d') : null;

    
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

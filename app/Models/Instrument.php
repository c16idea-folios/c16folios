<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Instrument extends Model
{
    protected $table='instruments';
    protected $guarded= ['id'];

    public function instrumentActs()
    {
        return $this->hasMany(InstrumentAct::class);
    }

      // Relación con el modelo User (responsable)
      public function responsible()
      {
          return $this->belongsTo(User::class, 'responsible_id');
      }

    /**
     * Relación con el modelo InstrumentAct, se mantiene por compatibilidad
     * acts() es ambiguo, pues parece hacer referencia al modelo Act, cuando realmente hace
     * referencia al modelo InstrumentAct
     * @deprecated
     */
    public function acts()
    {
        return $this->hasMany(InstrumentAct::class, 'instrument_id');
    }

public function getDataTable(Request $request, $status = "active")
{
    $columns = array(
        0 => 'id',
        1 => 'record',
        2 => 'payments',
        3 => 'notices',
        4 => 'delivered',
        5 => 'type',
        6 => 'no',
        7 => 'created_at_f',
        8 => 'acts',
        9 => 'clients',
        10 => 'total',
        11 => 'paid',
        12 => 'pending',
        13 => 'responsible',
    );

    // Filtra el total de datos basados en el status
    $totalData = Instrument::where('status', $status)->count();
    $totalFiltered = $totalData;

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');
    $dir = ($dir == 'desc') ? true : false;

    $items = [];
    if (empty($request->input('search.value'))) {
        if ($limit == -1) {
            $items = Instrument::where('status', $status)
                ->get(['*'])
                ->map(function ($item) {
                    return $this->mapDataTable($item);
                })
                ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                ->values()
                ->all();
        } else {
            $items = Instrument::where('status', $status)
                ->get(['*'])
                ->map(function ($item) {
                    return $this->mapDataTable($item);
                })
                ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                ->skip($start)
                ->take($limit)
                ->values()
                ->all();
        }
    } else {
        $search = $request->input('search.value');
        if ($limit == -1) {
            $items = Instrument::where('status', $status)
                ->get(['*'])
                ->map(function ($item) {
                    return $this->mapDataTable($item);
                })
                ->filter(function ($item) use ($search, $columns, $request) {
                    return $this->filterSearch($item, $search, $columns, $request);
                })
                ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                ->values()
                ->all();
        } else {
            $items = Instrument::where('status', $status)
                ->get(['*'])
                ->map(function ($item) {
                    return $this->mapDataTable($item);
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

        $totalFiltered = Instrument::where('status', $status)
            ->get(['*'])
            ->map(function ($item) {
                return $this->mapDataTable($item);
            })
            ->filter(function ($item) use ($search, $columns, $request) {
                return $this->filterSearch($item, $search, $columns, $request);
            })
            ->count();
    }

    $result = [
        'iTotalRecords'        => $totalData,
        'iTotalDisplayRecords' => $totalFiltered,
        'aaData'               => $items
    ];

    return $result;
}


    function getForSelect(){




        $dir =true;
        $items = Instrument::get(['*'])->map(function ($item) {
            return $this->mapDataTable($item);
        })->sortBy('no', SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
        return $items;
    }

    function mapDataTable($item)
    {
        $item["record"] = $item->id;
        $item["payments"] = $item->id;
        $item["notices"] = $item->id;
        $item["delivered"] = $item->id;

        $item["created_at_f"] = $item->created_at ? $item->created_at->format('Y-m-d') : null;
        $color = "#000000";
        $user = User::where('id', $item->responsible_id)->first();

        if ($user && $user->work_team_id != null) {
            $work_team = WorkTeam::where('id', $user->work_team_id)->first();
            $color = $work_team->identifier;
        }

        // Cargar instrumentActs con las relaciones de 'act' y 'client' en una sola consulta
        $instrument = Instrument::with(['instrumentActs.act', 'instrumentActs.client'])->findOrFail($item->id);

        // Generar el HTML para las listas desordenadas de 'acts' y 'clients'
        $htmlActs = '<ul style="color: ' . $color . ';">';
        $htmlClients = '<ul>';
        $acts_list=[];
        $total=0;
        $totalPaid=0;
        $item["show_notification"]=false;
        $item["acts_formated"]="";
        $item["clients_formated"]="";

        foreach ($instrument->instrumentActs as $instrumentAct) {


            // Generar HTML para los 'acts'
            if ($instrumentAct->act) {
                $htmlActs .= '<li>' . htmlspecialchars($instrumentAct->act->act) . '</li>';
                $item["acts_formated"].=$instrumentAct->act->act.", ";
                if(mb_strtolower($instrumentAct->act->act,'UTF-8') == mb_strtolower("constitución",'UTF-8')){
                    $item["show_notification"]=true;

                }
            }

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
                    $denomination = Denomination::find($client['denomination_id']);
                    if ($denomination) {
                        $clientName = $client["name"] . " " . $denomination->acronym;
                    }



                    array_push($acts_list,["text"=> strtoupper($client["name"])." (".$instrumentAct->act->act.")", "id"=> $instrumentAct->id,"is_foreigner"=> $isForeigner, "show_in_notification"=>(($instrumentAct->act->act=="Constitución")?true:false)] );
                } else if ($client['person_type'] == "física") {
                    $clientName = $client["name"] . " " . $client["last_name"] . " " . $client["second_last_name"];
                    array_push($acts_list, ["text"=>strtoupper($clientName)." (".$instrumentAct->act->act.")","id"=>$instrumentAct->id,"is_foreigner"=> $isForeigner, "show_in_notification"=>(($instrumentAct->act->act=="Constitución")?true:false) ]);
                } else {
                    $clientName = "";
                }

                $htmlClients .= '<li>' . strtoupper(htmlspecialchars($clientName)) . '</li>';
                $item["clients_formated"].=$clientName.", ";
            }


            if($instrumentAct->invoice=="request"){
                $item["cost_vat"] = round($instrumentAct->cost * 1.16, 2);


            } else if($instrumentAct->invoice=="sent"){
                $item["cost_vat"] = round($instrumentAct->cost * 1.16, 2);

            } else {
                $item["cost_vat"] = $instrumentAct->cost;

            }

            $total+= $item["cost_vat"];
            $payments=Payment::where('instrument_act_id',$instrumentAct->id)->get(["*"]);
            if(count($payments)>0){


                foreach ($payments as $key => $pay) {
                    $totalPaid+=$pay->amount_paid;
                }
            }



        }


        $htmlActs .= '</ul>';
        $htmlClients .= '</ul>';

        $item["acts_list"]=$acts_list;
        // Asignar los HTML generados al item
        $item["acts"] = $htmlActs;
        $item["clients"] = $htmlClients;


        // Agregar información adicional

        $item["total"]=round($total, 2) ;

        $item["total_formated"]=$this->formatCurrency(round($total, 2) );

        $item["paid"]=round($totalPaid, 2) ;
        $item["pending"]=round($total -  $totalPaid, 2) ;

        $item["responsible"] =($user)? $user->name . ' ' . $user->last_name . ' ' . $user->second_last_name:"";
        $item["delivered_status"] =(($item->submission_date!="" && $item->submission_date!=null)? "Entregado":"Sin entregar");

        return $item;
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

    function formatCurrency($amount)
    {
        // Verifica si el monto es válido
        if (!is_numeric($amount)) {
            return '$0.00';
        }

        // Formatea el número a formato de moneda en USD
        return number_format($amount, 2, '.', ',');
    }



}

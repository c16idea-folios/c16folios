<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Appearer extends Model
{
    protected $table='appearer';
    protected $guarded= ['id'];

    public function instrumentAct()
    {
        return $this->belongsTo(InstrumentAct::class);
    }

    public function getDataTable(Request $request)
    {


        $columns = array(
            0 => 'id',
            1 => 'act',
            2 => 'client',
            3 => 'appearer',
            4 => 'legal_representative',
            5 => 'observations'
        );

        $totalData = Appearer::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;


        $items = [];
        if (empty($request->input('search.value'))) {

            if ($limit == -1) {
                $items = Appearer::get(['*'])->map(function ($item) {
                        return $this->mapDataTable($item);
                    })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $items = Appearer::get(['*'])->map(function ($item) {
                        return $this->mapDataTable($item);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)
                    ->values()->all();
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $items =  Appearer::get(['*'])->map(function ($item) {
                        return $this->mapDataTable($item);
                    })
                    ->filter(function ($item) use ($search, $columns, $request) {
                        return $this->filterSearch($item, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {

                $items =  Appearer::get(['*'])->map(function ($item) {
                        return $this->mapDataTable($item);
                    })
                    ->filter(function ($item) use ($search, $columns, $request) {
                        return $this->filterSearch($item, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)
                    ->values()->all();
            }

            $totalFiltered = Appearer::get(['*'])->map(function ($item) {
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

        $instrumentAct = InstrumentAct::find($item->instrument_act_id); 
        $act = Act::find($instrumentAct->act_id); 
        $client = Client::find($instrumentAct->client_id); 
        $appearer = Client::find($item->appearer); 

        $item["act"]=$act->act;

           if( $client['person_type']=="moral"){
          
               $item["client"]=$client["name"];
        
           }else if( $client['person_type']=="física"){
            $item["client"]=$client["name"]." ".$client["last_name"]." ".$client["second_last_name"];
           }else{
               $item["client"]="";
           }


           if( $appearer['person_type']=="moral"){
          
            $item["appearer"]=$appearer["name"];
     
        }else if( $appearer['person_type']=="física"){
         $item["appearer"]=$appearer["name"]." ".$appearer["last_name"]." ".$appearer["second_last_name"];
        }else{
            $item["appearer"]="";
        }
        $item["appearer_id"]=$appearer["id"];
        $item["appearer_person_type"]=$appearer['person_type'];


        


      

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

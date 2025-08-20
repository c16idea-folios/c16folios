<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Client extends Model
{
    protected $table='clients';
    protected $guarded= ['id'];


    public function denomination()
    {
        return $this->belongsTo(Denomination::class, 'denomination_id');
    }

    public function instrumentActs()
    {
        return $this->hasMany(InstrumentAct::class);
    }
    public function getDataTable(Request $request)
    {


        $columns = array(
            0 => 'id',
            1 => 'person_type',
            2 => 'rfc',
            3 => 'client',
            4 => 'phone_number',
            5 => 'email',
            6 => 'country',
            7 => 'residence',
            8 => 'observations',
            9 => 'legal_representative',
        );

        $totalData = Client::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;


        $items = [];
        if (empty($request->input('search.value'))) {

            if ($limit == -1) {
                $items = Client::get(['*'])->map(function ($item) {
                        return $this->mapDataTable($item);
                    })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $items = Client::get(['*'])->map(function ($item) {
                        return $this->mapDataTable($item);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)
                    ->values()->all();
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $items =  Client::get(['*'])->map(function ($item) {
                        return $this->mapDataTable($item);
                    })
                    ->filter(function ($item) use ($search, $columns, $request) {
                        return $this->filterSearch($item, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {

                $items =  Client::get(['*'])->map(function ($item) {
                        return $this->mapDataTable($item);
                    })
                    ->filter(function ($item) use ($search, $columns, $request) {
                        return $this->filterSearch($item, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)
                    ->values()->all();
            }

            $totalFiltered = Client::get(['*'])->map(function ($item) {
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

        $item['item']=json_decode($item);

        if( $item['person_type']=="moral"){
         $denomination = Denomination::find($item['denomination_id']);
         if( $denomination){
            $item["client"]=$item["name"]." ".$denomination->acronym;
         }

        }else if( $item['person_type']=="física"){
         $item["client"]=$item["name"]." ".$item["last_name"]." ".$item["second_last_name"];
        }else{
            $item["client"]="";
        }
        $item["residence"]= $item["street"]." ".$item["n_exterior"]." ".$item["suburb"]." ".$item["municipality"]." ".$item["country"]." ".$item["zip_code"];

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

    public function getFormattedNameAttribute()
    {
        $name = '';
        if ($this->person_type === "moral") {
            $name = $this->name .
                ($this->denomination ? " " . $this->denomination->acronym : '');
        } elseif ($this->person_type === "física") {
            $name = $this->name . " " . $this->last_name . " " . $this->second_last_name;
        }

        return strtoupper(trim($name));
    }

}

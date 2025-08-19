<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class NoticeType extends Model
{
    protected $table='notice_type';
    protected $guarded= ['id'];


    public function getDataTable(Request $request)
    {


        $columns = array(
            0 => 'id',
            1 => 'act',
            2 => 'type',
            3 => 'days',
            4 => 'observations',
            5 => 'foreigners',
        );

        $totalData = NoticeType::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;


        $items = [];
        if (empty($request->input('search.value'))) {

            if ($limit == -1) {
                $items = NoticeType::get(['*'])->map(function ($item) {
                        return $this->mapDataTable($item);
                    })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $items = NoticeType::get(['*'])->map(function ($item) {
                        return $this->mapDataTable($item);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)
                    ->values()->all();
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $items =  NoticeType::get(['*'])->map(function ($item) {
                        return $this->mapDataTable($item);
                    })
                    ->filter(function ($item) use ($search, $columns, $request) {
                        return $this->filterSearch($item, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {

                $items =  NoticeType::get(['*'])->map(function ($item) {
                        return $this->mapDataTable($item);
                    })
                    ->filter(function ($item) use ($search, $columns, $request) {
                        return $this->filterSearch($item, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)
                    ->values()->all();
            }

            $totalFiltered = NoticeType::get(['*'])->map(function ($item) {
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

        $act = Act::find($item['act_id']); 
  
           $item["act"]=$act->act;
    
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

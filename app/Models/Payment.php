<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use NumberFormatter;
use Carbon\Carbon;

class Payment extends Model
{
    protected $table='payment';
    protected $guarded= ['id'];


    public function getDataTable(Request $request, $instrument_id = null)
    {
        $columns = array(
            0 => 'id',
            1 => 'id',
            2 => 'act',
            3 => 'client',
            4 => 'payment_date_f',
            5 => 'received_from',
            6 => 'amount_paid',
            7 => 'observations'
        );
    
        $query = Payment::query();
    
        // Si el $instrument_id no es null, filtrar por el instrument_id
        if ($instrument_id !== null) {
            $query->select('payment.*')->join('instrument_act', 'instrument_act.id', '=', 'payment.instrument_act_id')
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
            2 => 'act',
            3 => 'client',
            4 => 'cost_vat',
            5 => 'amount_paid',
            6 => 'pending',
            7 => 'invoice'
        );
    
        $query = Payment::query();
    
        // Si el $instrument_id no es null, filtrar por el instrument_id
        if ($instrument_id !== null) {
            $query->select('payment.*')
                  ->join('instrument_act', 'instrument_act.id', '=', 'payment.instrument_act_id')
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
    
        $status = $request->input('status');  // Obtener el valor de 'status' del request
        $items = [];
    
        if (empty($request->input('search.value'))) {
            // Obtener todos los datos sin ordenar
            $items = $query->get(['*'])->map(function ($item) use ($status) {
                $mappedItem = $this->mapDataTable2($item);
    
                // Filtrar según el status
                if ($status === 'Pagado' && $mappedItem['pending'] > 0) {
                    return null;  // No incluir si no está pagado
                } elseif ($status === 'Pendiente' && $mappedItem['pending'] <= 0) {
                    return null;  // No incluir si no está pendiente
                }
    
                return $mappedItem;  // Incluir si es el status correcto o si "Todos"
            });
    
            // Eliminar valores nulos y luego ordenar
            $items = $items->filter()->values();
            $items = $items->sortBy(function($item) use ($order) {
                return $item[$order];  // Ordenar por la columna seleccionada
            }, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values();
    
            if ($limit != -1) {
                $items = $items->slice($start, $limit)->values();  // Aplicar paginación
            }
    
            $totalFiltered = $items->count();  // Recalcular el total de elementos filtrados
        } else {
            $search = $request->input('search.value');
            $items = $query->get(['*'])->map(function ($item) use ($status) {
                $mappedItem = $this->mapDataTable2($item);
    
                // Filtrar según el status
                if ($status === 'Pagado' && $mappedItem['pending'] > 0) {
                    return null;  // No incluir si no está pagado
                } elseif ($status === 'Pendiente' && $mappedItem['pending'] <= 0) {
                    return null;  // No incluir si no está pendiente
                }
    
                return $mappedItem;  // Incluir si es el status correcto o si "Todos"
            });
            $items = $items->filter()->values();
            // Filtrar por la búsqueda
            $items = $items->filter(function ($item) use ($search, $columns, $request) {
                return $this->filterSearch($item, $search, $columns, $request);
            });
    
            // Ahora aplica el ordenamiento
            $items = $items->sortBy(function($item) use ($order) {
                return $item[$order];  // Ordenar por la columna seleccionada
            }, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values();
    
            if ($limit != -1) {
                $items = $items->slice($start, $limit)->values();  // Aplicar paginación
            }
    
            $totalFiltered = $items->count();  // Recalcular el total de elementos filtrados
        }
    
        $result = [
            'iTotalRecords'        => $totalData,
            'iTotalDisplayRecords' => $totalFiltered,
            'aaData'               => $items
        ];
    
        return $result;
    }
    
    

    public function getDataTable2Tmp(Request $request, $instrument_id = null)
    {
        $columns = array(
            0 => 'id',
            1 => 'no',
            2 => 'act',
            3 => 'client',
            4 => 'cost_vat',
            5 => 'amount_paid',
            6 => 'pending',
            7 => 'invoice'
        );
    
        $query = Payment::query();
    
        // Si el $instrument_id no es null, filtrar por el instrument_id
        if ($instrument_id !== null) {
            $query->select('payment.*')->join('instrument_act', 'instrument_act.id', '=', 'payment.instrument_act_id')
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
                        return $this->mapDataTable2($item);
                    })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $items = $query->get(['*'])->map(function ($item) {
                        return $this->mapDataTable2($item);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)
                    ->values()->all();
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $items = $query->get(['*'])->map(function ($item) {
                        return $this->mapDataTable2($item);
                    })
                    ->filter(function ($item) use ($search, $columns, $request) {
                        return $this->filterSearch($item, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $items = $query->get(['*'])->map(function ($item) {
                        return $this->mapDataTable2($item);
                    })
                    ->filter(function ($item) use ($search, $columns, $request) {
                        return $this->filterSearch($item, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)
                    ->values()->all();
            }
    
            $totalFiltered = $query->get(['*'])->map(function ($item) {
                return $this->mapDataTable2($item);
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
    $item["payment_date_f"]=$item->payment_date;




        return   $item;
    }



    function mapDataTable2($item)
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
    $item["payment_date_f"]=$item->payment_date;


    //
 

    if($instrumentActTmp->invoice=="request"){
        $item["cost_vat"] = round($instrumentActTmp->cost * 1.16, 2);
        $item["invoice"] = "Solicitar";

    } else if($instrumentActTmp->invoice=="sent"){
        $item["cost_vat"] = round($instrumentActTmp->cost * 1.16, 2);
        $item["invoice"] = "Enviada";
    } else {
        $item["cost_vat"] = $instrumentActTmp->cost;
        $item["invoice"] = "No aplica";
    }

    $item["pending"] =round($item["cost_vat"] -  $item["amount_paid"], 2) ;



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




public function generatePaymentDetails($payment)
{
    // Obtenemos el instrument_act relacionado
    $instrumentActTmp = InstrumentAct::where('id', $payment->instrument_act_id)->first();

    if (!$instrumentActTmp) {
        return null; // En caso de error
    }

    // Formatear la cantidad (amount_paid) en texto
    $amountInText = $this->formatAmountWithText($payment->amount_paid);

    // Formatear la fecha
    setlocale(LC_TIME, 'es_ES.UTF-8'); 
    $paymentDate = Carbon::parse($payment->payment_date);
        $formattedDate = $paymentDate->formatLocalized('%e de %B del %Y'); // Ejemplo: "16 de enero del 2025"

// Convertir solo el mes a mayúsculas sin afectar "de" ni "del"
$formattedDate = preg_replace_callback('/(\bde\b|\bdel\b|[a-záéíóúñ]+)/', function($matches) {
    if (in_array(strtolower($matches[0]), ['de', 'del'])) {
        return strtolower($matches[0]);  // Mantener 'de' y 'del' en minúsculas
    } elseif (strlen($matches[0]) > 1) {
        return ucfirst(strtolower($matches[0]));  // Solo hacer mayúscula la primera letra del mes
    }
    return $matches[0];  // Devolver tal cual si no es un mes
}, $formattedDate);

$client_name="";
        $client = Client::find($instrumentActTmp->client_id);
        if( $client['person_type']=="moral"){
            $denomination = Denomination::find($client['denomination_id']); 
            if( $denomination){
                $client_name=$client["name"]." ".$denomination->acronym;
            }
        
           }else if( $client['person_type']=="física"){
            $client_name=$client["name"]." ".$client["last_name"]." ".$client["second_last_name"];
           }else{
            $client_name="";
           }
    // Armar la información
    $details = [
        'received_from' => $payment->received_from ?? '',
        'amount_paid_text' => $amountInText,
        'act' => $instrumentActTmp->act->act ?? '',
        'client' => strtoupper($client_name),
        'policy_id' => $payment->instrument_act_id,
        'num' => $this->formatNumberWithLeadingZeros($payment->id),
        'instrument_id' => $instrumentActTmp->instrument_id,
        
        'formatted_date' => "Tlalnepantla de Baz, Estado de México a {$formattedDate}",
    ];

    return $details;
}

public function formatAmountWithText($number)
{
    // Dividir el número en enteros y decimales
    $formatter = new NumberFormatter('es_MX', NumberFormatter::SPELLOUT);

    $integerPart = (int) $number; // Parte entera
    $decimalPart = round(($number - $integerPart) * 100); // Parte decimal (dos dígitos)

    // Formatear como texto
    $integerText = ucfirst($formatter->format($integerPart)); // "Cien"
    $decimalText = str_pad($decimalPart, 2, '0', STR_PAD_LEFT); // Asegura dos dígitos ("00")

    // Construir el formato final
    return sprintf(
        '$ %.2f pesos moneda nacional (%s pesos %s/100 MN)',
        $number,
        $integerText,
        $decimalText
    );
}

public function formatNumberWithLeadingZeros($number, $length = 8)
{
    // Aseguramos que el número sea un entero
    $number = (int) $number;

    // Formateamos el número con ceros a la izquierda
    return str_pad($number, $length, '0', STR_PAD_LEFT);
}

//////////////////////////////////////////////////////


public function getDataTableReport(Request $request)
{
    $columns = array(
        0 => 'rfc',
        1 => 'client',
        2 => 'procedures',
        3 => 'payments',
    );

    $query = Client::query();
    $query->with('instrumentActs')->has('instrumentActs'); // Filtrar por instrument_id

    $totalData = $query->count();
    $totalFiltered = $totalData;

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');
    $dir = ($dir == 'desc') ? true : false;

    // Obtener el año del request
    $year = $request->input('year');

    $items = [];
    if (empty($request->input('search.value'))) {
        if ($limit == -1) {
            $items = $query->get(['*'])->map(function ($item) use ($year) {
                    return $this->mapDataTableReport($item, $year);
                })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
        } else {
            $items = $query->get(['*'])->map(function ($item) use ($year) {
                    return $this->mapDataTableReport($item, $year);
                })
                ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                ->skip($start)->take($limit)
                ->values()->all();
        }
    } else {
        $search = $request->input('search.value');
        if ($limit == -1) {
            $items = $query->get(['*'])->map(function ($item) use ($search, $year) {
                    return $this->mapDataTableReport($item, $year);
                })
                ->filter(function ($item) use ($search, $columns, $request) {
                    return $this->filterSearch($item, $search, $columns, $request);
                })
                ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
        } else {
            $items = $query->get(['*'])->map(function ($item) use ($search, $year) {
                    return $this->mapDataTableReport($item, $year);
                })
                ->filter(function ($item) use ($search, $columns, $request) {
                    return $this->filterSearch($item, $search, $columns, $request);
                })
                ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                ->skip($start)->take($limit)
                ->values()->all();
        }

        $totalFiltered = $query->get(['*'])->map(function ($item) use ($year) {
            return $this->mapDataTableReport($item, $year);
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


function mapDataTableReport($item, $year)
{
    // Sumatoria total de pagos del cliente
    $totalPayments = 0;

    // Iterar sobre los instrumentActs
    foreach ($item->instrumentActs as $instrumentAct) {
        // Filtrar los pagos por año
        $paymentsForYear = $instrumentAct->payments->filter(function ($payment) use ($year) {
            return $payment->payment_date && \Carbon\Carbon::parse($payment->payment_date)->year == $year;
        });

        // Sumar los pagos para el año específico
        $totalPayments += $paymentsForYear->sum('amount_paid');
    }

    $nameClient = "";
    if ($item['person_type'] == "moral") {
        $nameClient = $item['name'];
    } elseif ($item['person_type'] == "física") {
        $nameClient = $item['name'] . " " . $item['last_name'] . " " . $item['second_last_name'];
    } else {
        $nameClient = "";
    }

    return [
        'rfc' => $item->rfc,
        'client' => strtoupper($nameClient),
        'procedures' => $item->instrumentActs->count(),
        'payments' => $totalPayments, // Sumatoria total de pagos del cliente
    ];
}


}

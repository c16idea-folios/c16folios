<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class Calendar extends Model
{

    protected $table = 'calendar';
    protected $guarded = ['id'];
    public function getDataTable(Request $request)
    {
        $columns = [
            0 => 'date',   // Columna "date"
            1 => 'holiday' // Columna "holiday"
        ];
    
        $year = $request->year; // Año actual como predeterminado
        $totalData = Carbon::createFromDate($year, 12, 31)->dayOfYear; // Total de días en el año
        $limit = $request->input('length', 10); // Número de filas por página
        $start = $request->input('start', 0);   // Inicio de la paginación
        $order = $columns[$request->input('order.0.column', 0)]; // Columna para ordenar
        $dir = $request->input('order.0.dir', 'asc') === 'asc' ? 'asc' : 'desc';
        $search = strtoupper($request->input('search.value', ''));
    
        // Generar todas las fechas del año
        $dates = $this->generateYearlyCalendar($year);
    
        // Filtrar resultados si hay búsqueda
        if (!empty($search)) {
            $dates = $dates->filter(function ($item) use ($search) {
                return str_contains($item['date'], $search) ||
                       str_contains($item['holiday'], $search);
            });
        }
    
        // Total de registros filtrados
        $totalFiltered = $dates->count();
    
        // Ordenar resultados
        if ($order === 'date') {

            $dates = $dates->sort(function ($a, $b) use ($dir) {

          
                $dateA =$this->convertSpanishDateToCarbon( $a['date']);
                $dateB = $this->convertSpanishDateToCarbon( $b['date']);
                return $dir === 'asc' ? $dateA->greaterThan($dateB) : $dateB->greaterThan($dateA);
            });
        } else {
            $dates = $dir === 'asc'
                ? $dates->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE)
                : $dates->sortByDesc($order, SORT_NATURAL | SORT_FLAG_CASE);
        }
    
        // Aplicar paginación
        $dates = $dates->slice($start, $limit == -1 ? $totalFiltered : $limit)->values();
    
        return [
            'iTotalRecords' => $totalData, // Total de registros
            'iTotalDisplayRecords' => $totalFiltered, // Total filtrados
            'aaData' => $dates // Datos a mostrar
        ];
    }
    
    function generateYearlyCalendar($year)
    {
        setlocale(LC_TIME, 'es_ES.UTF-8'); 
        // Crear una lista de fechas para el año dado
        $dates = [];
        $startDate = Carbon::createFromDate($year, 1, 1);
        $endDate = Carbon::createFromDate($year, 12, 31);
    
        // Obtener los días festivos del año desde la base de datos
        $holidays = Calendar::whereYear('day', $year)
            ->get(['day', 'holiday']) // Solo obtenemos los campos necesarios
            ->keyBy(function ($item) {
                return Carbon::parse($item->day)->format('Y-m-d');
            });
    
        // Recorrer todos los días del año, generando el calendario completo
        while ($startDate->lte($endDate)) {
            $formattedDate = $startDate->formatLocalized('%d-%b-%Y');
            
            $holidayStatus = $holidays->has($startDate->toDateString())
                ? $holidays[$startDate->toDateString()]->holiday
                : 'No';
    
            $dates[] = [
                'date' => strtoupper($formattedDate),
                'holiday' => $holidayStatus
            ];
    
            $startDate->addDay();
        }
    
        return collect($dates); // Devolver como colección
    }
    
    function convertSpanishDateToCarbon($dateString, $format = 'd-M-Y') {
        $monthMap = [
            'ENE' => 'JAN',
            'FEB' => 'FEB',
            'MAR' => 'MAR',
            'ABR' => 'APR',
            'MAY' => 'MAY',
            'JUN' => 'JUN',
            'JUL' => 'JUL',
            'AGO' => 'AUG',
            'SEP' => 'SEP',
            'OCT' => 'OCT',
            'NOV' => 'NOV',
            'DIC' => 'DEC',
        ];
    
        // Reemplazar los meses en español por inglés
        foreach ($monthMap as $es => $en) {
            $dateString = str_replace($es, $en, $dateString);
        }
    
        // Convertir a Carbon
        return \Carbon\Carbon::createFromFormat($format, $dateString);
    }
}

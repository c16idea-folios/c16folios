<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\Request;
class User extends Authenticatable
{   use HasRoles;
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
 
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'username',
        'last_name',
        'second_last_name',
        'tel',
        'is_active',
        'profile_photo_path',
        'equipment',
        'password',
        'observations',
        'created_at',
        'expires',
        'work_team_id',


    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function instruments()
    {
        return $this->hasMany(Instrument::class, 'responsible_id');
    }

    public function getDataTable(Request $request)
    {



        $columns = array(
            0 => 'id',
            1 => 'profile_photo_path',
            2 => 'username',
            3 => 'name',
            4 => 'last_name',
            5 => 'second_last_name',
            6 => 'tel',
            7 => 'email',
            8 => 'rol',
            9 => 'team',
            10 => 'status',
            11 => 'created_at',
            12 => 'expires',
            13 => 'observations'
        );

        $totalData = User::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $dir = ($dir == 'desc') ? true : false;


        $items = [];
        if (empty($request->input('search.value'))) {

            if ($limit == -1) {
                $items = User::get(['*'])->map(function ($item) {
                        return $this->mapDataTable($item);
                    })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {
                $items = User::get(['*'])->map(function ($item) {
                        return $this->mapDataTable($item);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)
                    ->values()->all();
            }
        } else {
            $search = $request->input('search.value');
            if ($limit == -1) {
                $items =  User::get(['*'])->map(function ($item) {
                        return $this->mapDataTable($item);
                    })
                    ->filter(function ($item) use ($search, $columns, $request) {
                        return $this->filterSearch($item, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
            } else {

                $items =  User::get(['*'])->map(function ($item) {
                        return $this->mapDataTable($item);
                    })
                    ->filter(function ($item) use ($search, $columns, $request) {
                        return $this->filterSearch($item, $search, $columns, $request);
                    })
                    ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)
                    ->skip($start)->take($limit)
                    ->values()->all();
            }

            $totalFiltered = User::get(['*'])->map(function ($item) {
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

        $roles = $item->getRoleNames();
      
        if($item->work_team_id!=null){
            $workTeam = WorkTeam::find($item->work_team_id); 
            if( $workTeam){
                $item["team"]=$workTeam->team;
             }else{
                $item["team"]="";
             }
        }else{
            $item["team"]="";
        }
        $item["status"]=($item->is_active!=0)?"Activo": "Inactivo";
        if (count($roles) > 0) {
            switch ($roles[0]) {
                case 'technical_support':
                    $item["rol"] = "Soporte Técnico";
                    break;
                case 'administrator':
                    $item["rol"] = "Administrador";
                    break;
                case 'operator':
                    $item["rol"] = "Operador";
                    break;
                default:
                    $item["rol"] = "Rol desconocido"; // Por si hay un rol no esperado
            }

            $item["rol_o"]= $roles[0];
        } else {
            $item["rol"] = "";
            $item["rol_o"]= "";
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

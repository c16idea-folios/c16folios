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


    public function appearerClient(){
        return $this->belongsTo(Client::class, 'appearer');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Casher extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    public function order(){
        return $this->hasMany('App\Models\order','casher_id');
    }
    public function waiter(){
        return $this->belongsTo('App\Models\User','waiter');
    }
    public function User(){
        return $this->belongsTo('App\Models\User','aut_id');
    }
    public function bank(){
        return $this->belongsTo('App\Models\bank','bank');
    }
    public function menu(){
        return $this->belongsTo('App\Models\menu','orders');
    }
    
}

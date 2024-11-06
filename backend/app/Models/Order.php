<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Order extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable=[
        'casher_id',
        'menu_id',
        'amount',
        'price',
        'order',
        'state',
        'id',
        'deleted_at'
    ];
    public function menu(){
        return $this->belongsTo('App\Models\menu','menu_id');
    }
}

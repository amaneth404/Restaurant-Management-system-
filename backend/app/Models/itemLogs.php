<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class itemLogs extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable=[
        'item',
'amount',
'price',
'sell_price',
'branch',
'referal_no',
'location',
        'id',
        'deleted_at'
    ];
    public function branch_data(){
        return $this->belongsTo('App\Models\branch','branch');
    }
    public function item(){
        return $this->belongsTo('App\Models\itemGroup','item');
    }
    public function StoreItem($request){
        $request->merge([
            'id'=>Str::uuid()
        ]);
        $Item=new itemLogs;
        return $Item->create($request->all());
    }
}

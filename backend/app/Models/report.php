<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class report extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable=[
        'item',
'items',
'branch',
"category",
'amount',
'price',
'type',
'sell_price',
'isreported',
'state',
        'id',
        'deleted_at'
    ];
    public function branch_data(){
        return $this->belongsTo('App\Models\branch','branch');
    }
    public function itemgroups(){
        return $this->belongsTo('App\Models\itemGroup','item');
    }
    const Updatereport=['title'];
    public function Storereport($request){
        $report=new report;
        return $report->create($request);
    }
    public function Updatereport($request,$report,$update_values){
        foreach($update_values as $value){
            $report->$value=$request->$value;
        }
        $report->save();
        return $report;
    }
}

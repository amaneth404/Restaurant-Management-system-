<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class reporttr extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable=[
        'item',
'items',
'branch',
'amount',
'type',
'price',
'isreturn',
'sell_price',
'isreported',
'state',
        'id',
        'deleted_at'
    ];
    public function branch_data(){
        return $this->belongsTo('App\Models\branch','branch');
    }
    public function item(){
        return $this->belongsTo('App\Models\itemGroup','item');
    }
    const Updatereport=['title'];
    public function Storereporttr($request){
        $report=new reporttr;
        return $report->create($request);
    }
    public function Updatereporttr($request,$report,$update_values){
        foreach($update_values as $value){
            $report->$value=$request->$value;
        }
        $report->save();
        return $report;
    }
}

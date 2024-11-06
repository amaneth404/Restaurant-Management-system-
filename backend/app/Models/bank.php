<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class bank extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable=[
        'name',
        'money',
        'isreported',
        'state',
        'id',
        'deleted_at'
    ];
    public function branch(){
        return $this->belongsTo('App\Models\branch','branch');
    }
    const Updatebank=['name'];
    public function Storebank($request){
        $request->merge([
            'id'=>Str::uuid()
        ]);
        $bank=new bank;
        return $bank->create($request->all());
    }
    public function Updatebank($request,$bank,$update_values){
        foreach($update_values as $value){
            $bank->$value=$request->$value;
        }
        $bank->save();
        return $bank;
    }
}

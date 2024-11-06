<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class item extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable=[
        'item',
        'amount',
        'price',
        'state',
        'type',
        'sold',
        'category',
        'sell_price',
        'branch',
        'id',
        'deleted_at'
    ];
    const UpdateItem=[
    'amount',
    'price',
    'sell_price',];
    public function branch_data(){
        return $this->belongsTo('App\Models\branch','branch');
    }
    public function item(){
        return $this->belongsTo('App\Models\itemGroup','item');
    }
    public function category_data(){
        return $this->belongsTo('App\Models\category','category');
    }
    public function itemgroups(){
        return $this->belongsTo('App\Models\itemGroup','item');
    }
    public function report(){
        return $this->hasMany('App\Models\item','items');
    }
    public function StoreItem($request){
        $request->merge([
            'id'=>Str::uuid(),
            'sold'=>0,
            'state'=>1
        ]);
        $Item=new item;
        return $Item->create($request->all());
    }
    public function UpdateItem($request,$Item,$update_values){
        foreach($update_values as $value){
            $Item->$value=$request->$value;
        }
        $Item->save();
        return $Item;
    }
}

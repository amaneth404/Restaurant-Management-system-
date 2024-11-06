<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
class RequestItem extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable=[
'items',
'item',
'branch',
'category',
'folder_id',
'amount',
'price',
'sell_price',
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
    const UpdateRequestItem=['title'];
    public function StoreRequestItem($request,$foldr_id){
        $holder=[];
        $holder['id']=Str::uuid();
        $holder['folder_id']=$foldr_id;
        $holder['items']=$request['items'];
        $holder['item']=$request['item'];
        $holder['branch']=$request['branch'];
        $holder['amount']=$request['amount'];
        $holder['price']=$request['price'];
        $holder['category']=$request['category'];
        $holder['sell_price']=$request['sell_price'];
        $RequestItem=new RequestItem;
        return $RequestItem->create($holder);
    }
    public function UpdateRequestItem($request,$RequestItem,$update_values){
        foreach($update_values as $value){
            $RequestItem->$value=$request->$value;
        }
        $RequestItem->save();
        return $RequestItem;
    }
}

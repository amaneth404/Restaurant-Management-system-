<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class itemGroup extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable=[
        'title',
        'category',
        'state',
        'unit',
        'id',
        'deleted_at'
    ];
    public function category(){
        return $this->belongsTo('App\Models\category','category');
    }
    public function items(){
        return $this->hasMany('App\Models\item','item');
    }
    const UpdateItemGroup=['title','category','unit'];
    public function StoreitemGroup($request){
        $request->merge([
            'id'=>Str::uuid(),
            'state'=>1
        ]);
        $item_group=new itemGroup;
        return $item_group->create($request->all());
    }
    public function UpdateitemGroup($request,$item_group,$update_values){
        foreach($update_values as $value){
            $item_group->$value=$request->$value;
        }
        $item_group->save();
        return $item_group;
    }
}

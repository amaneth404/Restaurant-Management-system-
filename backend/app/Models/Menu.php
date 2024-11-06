<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class Menu extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable=[
        'name',
        'cate_id',
        'from_data',
        'tokichen',
        'cost',
        'icon',
        'price',
        'id',
        'deleted_at'
    ];
    public function item(){
        return $this->belongsTo('App\Models\item','item_id');
    }
    public function casher(){
        return $this->hasMany('App\Models\Casher','orders');
    }
    const UpdateMenu=['name','icon','price','from_data','tokichen','cost'];
    public function StoreMenu($request,$file_name=''){
        $request=$request->except(['attachment']);
        if(!$file_name){
        $request['id']=Str::uuid();
        }else{
        $request['id']=Str::uuid();
        $request['icon']=$file_name;
        }
        $category=new Menu;
        return $category->create($request);
    }
    public function UpdateMenu($request,$category,$update_values,$file_name=''){
        foreach($update_values as $value){
            $category->$value=$request->$value;
        }
        if($file_name){
            $category->icon=$file_name;
        }
        $category->save();
        return $category;
    }
}

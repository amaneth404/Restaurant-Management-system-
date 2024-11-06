<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class MenuCategory extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable=[
        'name',
        'icon',
        'from',
        'id',
        'deleted_at'
    ];
    public function menu(){
        return $this->hasMany('App\Models\menu','cate_id');
    }
    const UpdateMenuCategory=['name','icon','from'];
    public function StoreMenuCategory($request,$file_name=''){
        $request=$request->except(['attachment']);
        if(!$file_name){
        $request['id']=Str::uuid();
        }else{
        $request['id']=Str::uuid();
        $request['icon']=$file_name;
        }
        $category=new MenuCategory;
        return $category->create($request);
    }
    public function UpdateMenuCategory($request,$category,$update_values,$file_name=''){
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

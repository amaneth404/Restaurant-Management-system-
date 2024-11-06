<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class Category extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable=[
        'title',
        'attachment',
        'id',
        'deleted_at'
    ];
    public function item(){
        return $this->hasMany('App\Models\itemGroup','category');
    }
    const UpdateCategory=['title'];
    public function StoreCategory($request,$file_name=''){
        $request=$request->except(['attachment']);
        if(!$file_name){
        $request['id']=Str::uuid();
        }else{
        $request['id']=Str::uuid();
        $request['attachment']=$file_name;
        }
        $category=new Category;
        return $category->create($request);
    }
    public function UpdateCategory($request,$category,$update_values,$file_name=''){
        foreach($update_values as $value){
            $category->$value=$request->$value;
        }
        if($file_name){
            $category->attachment=$file_name;
        }
        $category->save();
        return $category;
    }
}

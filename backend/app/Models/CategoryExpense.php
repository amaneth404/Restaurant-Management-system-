<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class CategoryExpense extends Model
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
    const UpdateCategoryExpense=['title'];
    public function StoreCategoryExpense($request,$file_name=''){
        $request=$request->except(['attachment']);
        if(!$file_name){
        $request['id']=Str::uuid();
        }else{
        $request['id']=Str::uuid();
        $request['attachment']=$file_name;
        }
        $category=new CategoryExpense;
        return $category->create($request);
    }
    public function UpdateCategoryExpense($request,$category,$update_values,$file_name=''){
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

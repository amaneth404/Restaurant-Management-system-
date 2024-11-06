<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class RequestItemFolder extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable=[
        'id',
        'casher_id',
        'deleted_at'
    ];
    public function storeRequest(){
        return $this->hasMany('App\Models\storeRequest','folder_id');
    }
    const UpdateRequestItemFolder=['title'];
    public function StoreRequestItemFolder(){
        $category=new RequestItemFolder;
        return $category->create($request);
    }
    public function UpdateRequestItemFolder($request,$category,$update_values,$file_name=''){
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

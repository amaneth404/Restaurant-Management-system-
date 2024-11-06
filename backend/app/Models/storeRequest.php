<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class storeRequest extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable=[
        'id',
        'folder_id',
        'item_id',
        'amount',
        'deleted_at'
    ];
    public function item(){
        return $this->belongsTo('App\Models\item','item_id');
    }
    const UpdateRequestItemFolder=['title'];
    public function StoreRequestItem(){
        $category=new RequestItemFolder;
        return $category->create($request);
    }
    public function UpdateRequestItem($request,$category,$update_values,$file_name=''){
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

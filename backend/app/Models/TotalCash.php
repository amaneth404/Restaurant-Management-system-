<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class TotalCash extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable=[
        'money',
        'id',
        'deleted_at'
    ];
    public function item(){
        return $this->hasMany('App\Models\itemGroup','category');
    }
    const UpdateTotalCash=['money'];
    public function StoreTotalCash($request,$file_name=''){
        $request['id']=Str::uuid();
        $category=new TotalCash;
        return $category->create($request->all());
    }
    public function UpdateTotalCash($request,$category,$update_values,$file_name=''){
        foreach($update_values as $value){
            $category->$value=$request->$value;
        }
       
        $category->save();
        return $category;
    }
}


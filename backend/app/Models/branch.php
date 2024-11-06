<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class branch extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable=[
        'title',
        'location',
        'state',
        'user',
        'id',
        'deleted_at'
    ];
    const Updatebranch=['title','location'];
    public function Storebranch($request){
        $request['id']=Str::uuid();
        $branch=new branch;
        return $branch->create($request);
    }
    public function Updatebranch($request,$branch,$update_values){
        foreach($update_values as $value){
            if(filled($request->$value)&&$request->$value!='null'){
            $branch->$value=$request->$value;
            }
        }
        $branch->save();
        return $branch;
    }
}

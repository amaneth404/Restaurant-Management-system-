<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class Salary extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable=[
        'money',
        'user_id',
        'id',
        'deleted_at'
    ];
    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function StoreSalary($user_id,$money){
        $holder['id']=Str::uuid();
        $holder['user_id']=$user_id;
        $holder['money']=$money;
        $salary=new Salary;
        return $salary->create($holder);
    }
   
}

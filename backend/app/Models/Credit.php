<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class Credit extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable=[
        'money',
        'phone_number',
        'full_name',
        'user_id',
        'id',
        'deleted_at'
    ];
    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function casher(){
        return $this->belongsTo('App\Models\User','casher');
    }
    public function StoreCredit($user_id,$money,$full_name,$phone_number){
        $holder['id']=Str::uuid();
        $holder['user_id']=$user_id;
        $holder['money']=$money;
        $holder['phone_number']=$phone_number;
        $holder['full_name']=$full_name;
        $Credit=new Credit;
        return $Credit->create($holder);
    }
   
}

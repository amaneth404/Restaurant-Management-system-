<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class UserPrediction extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable=[
        'user_id',
        'money',
        'phone_number',
        'id',
        'deleted_at'
    ];
    public function prediction(){
        return $this->belongsTo('App\Models\Predictions','user_pre_id');
    }
    const UpdateUserPrediction=['title','date'];
    public function StorePrediction($user_id,$money,$phone_number){
        $games=new UserPrediction;
        $games->id=Str::uuid();
        $games->money=$money;
        $games->user_id=$user_id;
        $games->phone_number=$phone_number;
        $games->save();
        return $games;
    }
    public function UpdatePrediction(){
        return '' ;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersStatus extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable=['updated_at'];
    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class UserLoginHistory extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
}

<?php

namespace App\Constants\AuthValidationRule;

class AuthRule
{
    const LoginRule=[
        'username'=>'required',
        'password'=>'required|min:8'
    ];

}

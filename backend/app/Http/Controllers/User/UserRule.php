<?php

namespace App\Http\Controllers\User;


class UserRule
{
    const LoginRule=[
        'username'=>'required',
        'password'=>'required|min:4'
    ];
    public static function ChangePhoneNumber($id){ return [
        'phone_number'=>'required|unique:users,phone_number,'.$id,
        'password'=>'required|confirmed|min:4'
    ];
}
public static function changeUsername($id){ return [
    'username'=>'required|unique:users,username,'.$id,
    'password'=>'required|confirmed|min:4'
];
}

    const RegisterRule=[
        'username'=>'required|unique:users',
        'location'=>'required',
        'title'=>'required',
        'role'=>'required',
        'key'=>'required',
        'password'=>'required|confirmed|min:4'
    ];
    const UpdateUsersRule=[
        'first_name'=>'required',
        'last_name'=>'required',
        'middle_name'=>'required'
    ];
    const ChangeEmployePasswordRule=[
        'password'=>'required|confirmed|min:4'
    ];
    const changePassword=[
        'password'=>'required|confirmed|min:4',
        'old_password'=>'required|min:4'
    ];
    const RegisterEmployeRule=[
        'username'=>'required|unique:users',
        'first_name'=>'required',
        'last_name'=>'required',
        'middle_name'=>'required',
        'phone_number'=>'required|unique:users',
        'password'=>'required|confirmed|min:4'
    ];
    public static function RegisterUpdateEmployeRule($id){
        return [
        'first_name'=>'required',
'last_name'=>'required',
'role'=>'required',
'middle_name'=>'required',
'phone_number'=>'required|unique:users,phone_number,'.$id,
'username'=>'required|unique:users,username,'.$id,
    ];
}
}

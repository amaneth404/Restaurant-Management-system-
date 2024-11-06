<?php

namespace App\Http\Controllers\RequestedItme\RequestedItmeRules;


class RequestedItmeRules
{
    const RequestedItmeStoreRule=[
        'requested'=>'required|array',
    ];
    public static function RequestedItmeUpdateRule($id){
        return [
        'title'=>'required',
    ];
}
}



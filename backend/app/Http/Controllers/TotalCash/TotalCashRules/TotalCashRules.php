<?php

namespace App\Http\Controllers\TotalCash\TotalCashRules;


class TotalCashRules
{
    const TotalCashStoreRule=[
        'money'=>'required'
    ];
    public static function TotalCashUpdateRule($id){
        return [
        'money'=>'required',
    ];
}
}



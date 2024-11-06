<?php

namespace App\Http\Controllers\Bank\BankRules;


class BankRules
{
    const BankStoreRule=[
        'name'=>'required|unique:banks'
    ];
    public static function BankUpdateRule($id){
        return [
        'name'=>'required|unique:banks,name,'.$id,
    ];
}
}



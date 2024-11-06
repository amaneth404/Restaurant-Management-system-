<?php

namespace App\Http\Controllers\Bank\BankRules;


class BankRules
{
    const BankStoreRule=[
        'money'=>'required',
        'bank_no'=>'required|unique:banks'
    ];
    public static function BankUpdateRule($id){
        return [
            'money'=>'required',
        'bank_no'=>'required|unique:banks,bank_no,'.$id,
    ];
}
}



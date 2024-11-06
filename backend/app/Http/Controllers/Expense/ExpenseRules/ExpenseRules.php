<?php

namespace App\Http\Controllers\Expense\ExpenseRules;


class ExpenseRules
{
    const ExpenseStoreRule=[
        'amount'=>'required',
    ];
    public static function ExpenseUpdateRule($id){
        return [
        'amount'=>'required',
    ];
}
}



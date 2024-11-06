<?php

namespace App\Http\Controllers\Casher\CasherRules;


class CasherRules
{
    const CasherStoreRule=[
        // 'table_name'=>'required',
        'order'=>'required|array'
    ];
    public static function CasherUpdateRule($id){
        return [
            // 'table_name'=>'required',
            // 'order'=>'required|array'
    ];
}
}



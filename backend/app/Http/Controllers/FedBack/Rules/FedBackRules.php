<?php

namespace App\Http\Controllers\FedBack\FedBackRules;


class FedBackRules
{
    const FedBackStoreRule=[
        'body'=>'required'
    ];
    public static function FedBackUpdateRule($id){
        return [
        'title'=>'required|unique:leagues,title,'.$id,
    ];
}
}

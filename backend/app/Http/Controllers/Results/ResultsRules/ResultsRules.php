<?php

namespace App\Http\Controllers\Results\ResultsRules;


class ResultsRules
{
    const ResultsStoreRule=[
        'game_id'=>'required'
    ];
    public static function ResultsUpdateRule($id){
        return [
            'game_id'=>'required'
    ];
}
}

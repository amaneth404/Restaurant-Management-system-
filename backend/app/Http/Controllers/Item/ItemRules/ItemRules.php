<?php

namespace App\Http\Controllers\Item\ItemRules;


class ItemRules
{
    const ItemStoreRule=[
        'item'=>'required',
'amount'=>'required',
'price'=>'required',
'sell_price'=>'required'
    ];
    public static function ItemUpdateRule($id){
        return [
            'item'=>'required',
            'amount'=>'required',
            'price'=>'required',
            'sell_price'=>'required'    ];
}
}

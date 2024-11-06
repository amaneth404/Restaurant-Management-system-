<?php

namespace App\Http\Controllers\Menu\MenuRules;


class MenuRules
{
    const MenuStoreRule=[
        'name'=>'required',
        'price'=>'required',
        'cate_id'=>'required'
    ];
    public static function MenuUpdateRule($id){
        return [
            'name'=>'required',
            'price'=>'required',
            'cate_id'=>'required'
    ];
}
}



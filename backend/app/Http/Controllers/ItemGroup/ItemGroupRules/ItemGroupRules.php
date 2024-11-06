<?php

namespace App\Http\Controllers\ItemGroup\ItemGroupRules;


class ItemGroupRules
{
    const ItemGroupStoreRule=[
        'title'=>'required|unique:item_groups',
        'category'=>'required'
    ];
    public static function ItemGroupUpdateRule($id){
        return [
        'title'=>'required|unique:item_groups,title,'.$id,
        'category'=>'required'
    ];
}
}

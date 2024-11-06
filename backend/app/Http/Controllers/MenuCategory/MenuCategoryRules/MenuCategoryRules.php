<?php

namespace App\Http\Controllers\MenuCategory\MenuCategoryRules;


class MenuCategoryRules
{
    const CategoryStoreRule=[
        'name'=>'required|unique:menu_categories'
    ];
    public static function CategoryUpdateRule($id){
        return [
        'name'=>'required|unique:menu_categories,name,'.$id,
    ];
}
}



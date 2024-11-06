<?php

namespace App\Http\Controllers\Category\CategoryRules;


class CategoryRules
{
    const CategoryStoreRule=[
        'title'=>'required'
    ];
    public static function CategoryUpdateRule($id){
        return [
        'title'=>'required',
    ];
}
}



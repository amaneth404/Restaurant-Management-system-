<?php

namespace App\Http\Controllers\MenuCategory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuCategory;
use App\Models\menu;
use Illuminate\Support\Carbon;
use App\Http\Controllers\MenuCategory\MenuCategoryRules\MenuCategoryRules;
use Illuminate\Support\Facades\URL;
use App\Services\FileService;
class CategoryController extends Controller
{
    public function index(){
        $Category=MenuCategory::orderBy('created_at', 'desc')->get();
        return $this->SuccessResponse($Category,200);
    }
    public function Search(Request $request){
        $Category=MenuCategory::where('title','like','%'.$request->search.'%')->orderBy('created_at', 'desc')->get();
        return $this->SuccessResponse($Category,200);
    }
    public function store(Request $request){
        $this->validate($request,MenuCategoryRules::CategoryStoreRule);
        $file_name='';
         $Category=(new MenuCategory)->StoreMenuCategory($request,$file_name);
        return $this->SuccessResponse($Category,200);
    }
    public function update(Request $request){
        $Category=MenuCategory::findOrfail($request->id);
        $this->validate($request,MenuCategoryRules::CategoryUpdateRule($Category->id));
        $file_name='';
        $MenuCategory=(new MenuCategory)->UpdateMenuCategory($request,$Category,MenuCategory::UpdateMenuCategory,$file_name);
        return $this->successResponse($MenuCategory,200);
    }
    public function destroy($id){
        $item=menu::where('cate_id',$id)->first();
        if($item){
            return $this->ErrorResponse(['error'=>["Category can't be deleted because it have Menu inside !!"]],422);
        }
        $Category=MenuCategory::findOrfail($id);
        if($Category->icon)
        unlink('storage/uploads/images/'.$Category->icon);
        $Category->forceDelete();
        return $this->SuccessResponse(['message'=>['Successfully deleted']],200);
    }
}

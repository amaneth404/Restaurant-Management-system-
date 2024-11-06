<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\category;
use App\Models\itemGroup;
use App\Models\CategoryExpense;

use Illuminate\Support\Carbon;
use App\Http\Controllers\Category\CategoryRules\CategoryRules;
use Illuminate\Support\Facades\URL;
use App\Services\FileService;
class CategoryController extends Controller
{
    public function index(){
        $Category=category::whereIn('state',[1,-1])->orderBy('created_at', 'desc')->get();
        return $this->SuccessResponse($Category,200);
    }
    public function CategoryWithitem(){
        $Category=category::where('state',1)->with('item')->orderBy('created_at', 'desc')->get();
        return $this->SuccessResponse($Category,200);
    }
    public function Search(Request $request){
        $Category=category::where('title','like','%'.$request->search.'%')->whereIn('state',[1,-1])->orderBy('created_at', 'desc')->get();
        return $this->SuccessResponse($Category,200);
    }
    public function store(Request $request){
        $this->validate($request,CategoryRules::CategoryStoreRule);
        $file_name='';
        if($request->attachment){
            if(!FileService::ExtensionTester($request->attachment,FileService::IMAGE)){
            return  $this->ErrorResponse(['attachment'=>['Unsuported Image Format']],422);
            }
            $file_name=FileService::ImageProccessore($request->attachment,FileService::IMAGE,'image')['filename'];
        }
         $Category=(new Category)->StoreCategory($request,$file_name);
        return $this->SuccessResponse($Category,200);
    }
    public function update(Request $request){
        $Category=category::findOrfail($request->id);
        $this->validate($request,CategoryRules::CategoryUpdateRule($Category->id));
        $file_name='';
        if($request->attachment){
            if(!FileService::ExtensionTester($request->attachment,FileService::IMAGE)){
            return  $this->ErrorResponse(['attachment'=>['Unsuported Image Format']],422);
            }
            $file_name=FileService::ImageProccessore($request->attachment,FileService::IMAGE,'image',$Category->image)['filename'];
        }
        $category=(new Category)->UpdateCategory($request,$Category,category::UpdateCategory,$file_name);
        return $this->successResponse($category,200);
    }
    public function destroy($id){
        $item=itemGroup::where('category',$id)->first();
        if($item){
            return $this->ErrorResponse(['error'=>["Category can't be deleted because it have items inside !!"]],422);
        }
        $Category=category::findOrfail($id);
        if($Category->attachment)
        unlink('storage/uploads/images/'.$Category->attachment);
        $Category->forceDelete();
        return $this->SuccessResponse(['message'=>['Successfully deleted']],200);
    }

    public function indexexpense(){
        $Category=CategoryExpense::whereIn('state',[1,-1])->orderBy('created_at', 'desc')->get();
        return $this->SuccessResponse($Category,200);
    }
    public function Searchexpense(Request $request){
        $Category=CategoryExpense::where('title','like','%'.$request->search.'%')->whereIn('state',[1,-1])->orderBy('created_at', 'desc')->get();
        return $this->SuccessResponse($Category,200);
    }
    

    public function storeexpense(Request $request){
        $this->validate($request,CategoryRules::CategoryStoreRule);
        $file_name='';
        if($request->attachment){
            if(!FileService::ExtensionTester($request->attachment,FileService::IMAGE)){
            return  $this->ErrorResponse(['attachment'=>['Unsuported Image Format']],422);
            }
            $file_name=FileService::ImageProccessore($request->attachment,FileService::IMAGE,'image')['filename'];
        }
         $Category=(new CategoryExpense)->StoreCategoryExpense($request,$file_name);
        return $this->SuccessResponse($Category,200);
    }
    public function updateexpense(Request $request){
        $Category=CategoryExpense::findOrfail($request->id);
        $this->validate($request,CategoryRules::CategoryUpdateRule($Category->id));
        $file_name='';
        if($request->attachment){
            if(!FileService::ExtensionTester($request->attachment,FileService::IMAGE)){
            return  $this->ErrorResponse(['attachment'=>['Unsuported Image Format']],422);
            }
            $file_name=FileService::ImageProccessore($request->attachment,FileService::IMAGE,'image',$Category->image)['filename'];
        }
        $category=(new CategoryExpense)->UpdateCategoryExpense($request,$Category,category::UpdateCategory,$file_name);
        return $this->successResponse($category,200);
    }
    public function destroyexpense($id){
        $item=itemGroup::where('category',$id)->first();
        if($item){
            return $this->ErrorResponse(['error'=>["Category can't be deleted because it have items inside !!"]],422);
        }
        $Category=CategoryExpense::findOrfail($id);
        if($Category->attachment)
        unlink('storage/uploads/images/'.$Category->attachment);
        $Category->forceDelete();
        return $this->SuccessResponse(['message'=>['Successfully deleted']],200);
    }
}

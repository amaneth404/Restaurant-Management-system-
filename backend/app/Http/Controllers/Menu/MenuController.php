<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\MenuCategory;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Menu\MenuRules\MenuRules;
use Illuminate\Support\Facades\URL;
use App\Services\FileService;
class MenuController extends Controller
{
    public function index($id){
        $Category=Menu::where('cate_id',$id)->orderBy('created_at', 'desc')->with('item')->get();
        return $this->SuccessResponse($Category,200);
    }
    public function getForLinking(){
        $Category=Menu::where('from_data',0)->orderBy('created_at', 'desc')->get();
        return $this->SuccessResponse($Category,200);
    }
    public function Search(Request $request,$id){
        $Category=Menu::where('cate_id',$id)->where('title','like','%'.$request->search.'%')->orderBy('created_at', 'desc')->get();
        return $this->SuccessResponse($Category,200);
    }
    public function store(Request $request){
        $this->validate($request,MenuRules::MenuStoreRule);
        $file_name='';
        if($request->attachment){
            if(!FileService::ExtensionTester($request->attachment,FileService::IMAGE)){
            return  $this->ErrorResponse(['attachment'=>['Unsuported Image Format']],422);
            }
            $file_name=FileService::ImageProccessore($request->attachment,FileService::IMAGE,'image')['filename'];
        }
         $Category=(new Menu)->StoreMenu($request,$file_name);
         if($request['time']){
            $Category->time=$request['time'];
            $Category->save();
         }
         $MenuCategory=MenuCategory::findorfail($Category->cate_id);
         $MenuCategory->counter+=1;
         $MenuCategory->save();
        return $this->SuccessResponse($Category,200);
    }
    public function update(Request $request){
        $Category=Menu::findOrfail($request->id);
        $this->validate($request,MenuRules::MenuUpdateRule($Category->id));
        $file_name='';
        if($request->attachment){
            if(!FileService::ExtensionTester($request->attachment,FileService::IMAGE)){
            return  $this->ErrorResponse(['attachment'=>['Unsuported Image Format']],422);
            }
            $file_name=FileService::ImageProccessore($request->attachment,FileService::IMAGE,'image',$Category->image)['filename'];
        }
        $Menu=(new Menu)->UpdateMenu($request,$Category,Menu::UpdateMenu,$file_name);
        if($request['time']){
            $Menu->time=$request['time'];
            $Menu->save();
         }
        return $this->successResponse($Menu,200);
    }
    public function destroy($id){
        $menu=Menu::findOrfail($id);
        if($menu->icon)
        unlink('storage/uploads/images/'.$menu->icon);
        $MenuCategory=MenuCategory::findorfail($menu->cate_id);
        $MenuCategory->counter-=1;
        $MenuCategory->save();
        $menu->forceDelete();
        return $this->SuccessResponse(['message'=>['Successfully deleted']],200);
    }
}

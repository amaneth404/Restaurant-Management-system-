<?php

namespace App\Http\Controllers\ItemGroup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\itemGroup;
use App\Models\item;
use App\Models\category;
use Illuminate\Support\Carbon;
use App\Http\Controllers\ItemGroup\ItemGroupRules\ItemGroupRules;
use App\Services\FileService;
use Illuminate\Support\Facades\URL;
class ItemGroupController extends Controller
{
    public function index(){
        $ItemGroup=itemGroup::whereIn('state',[1,-1])->with('category')->orderBy('created_at', 'desc')->paginate(200);
        return $this->SuccessResponse($ItemGroup,200);
    }
    public function indexAll(){
        $ItemGroup=itemGroup::whereIn('state',[1,-1])->with('category')->orderBy('created_at', 'desc')->get();
        return $this->SuccessResponse($ItemGroup,200);
    }
    public function Search(Request $request){
        $ItemGroup=itemGroup::whereIn('state',[1,-1])->where('title','like','%'.$request->search.'%')->with('category')->orderBy('created_at', 'desc')->paginate(200);
        return $this->SuccessResponse($ItemGroup,200);
    }
    public function store(Request $request){
        $this->validate($request,ItemGroupRules::ItemGroupStoreRule);
        $category=category::findorfail($request->category);
         $ItemGroup=(new itemGroup)->StoreItemGroup($request);
         $ItemGroup['category']=$category;
        return $this->SuccessResponse($ItemGroup,200);
    }
    public function update(Request $request,$id){
        $ItemGroup=itemGroup::findOrfail($id);
        $this->validate($request,ItemGroupRules::ItemGroupUpdateRule($ItemGroup->id));
        $category=category::findorfail($request->category);
        $ItemGroup=(new itemGroup)->UpdateItemGroup($request,$ItemGroup,ItemGroup::UpdateItemGroup);
        $ItemGroup['category']=$category;
        return $this->successResponse($ItemGroup,200);
    }
    public function DActivet(Request $request,$id){
        $ItemGroup=itemGroup::findOrfail($id);
        $ItemGroup->state=-1;
        $ItemGroup->save();
        return $this->successResponse('success',200);
    }
    public function Activet(Request $request,$id){
        $ItemGroup=itemGroup::findOrfail($id);
        $ItemGroup->state=1;
        $ItemGroup->save();
        return $this->successResponse('success',200);
    }
    public function destroy($id){
        $item=item::where('item',$id)->first();
        if($item){
            return $this->ErrorResponse(['error'=>["ItemGroup can't be deleted because it have items inside !!"]],422);
        }
        $ItemGroup=itemGroup::findOrfail($id);
        $ItemGroup->forceDelete();
        return $this->SuccessResponse(['message'=>['Successfully deleted']],200);
    }
}

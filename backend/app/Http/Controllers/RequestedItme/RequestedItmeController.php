<?php

namespace App\Http\Controllers\RequestedItme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RequestItem;
use App\Models\RequestItemFolder;
use App\Models\storeRequest;
use Illuminate\Support\Carbon;
use App\Http\Controllers\RequestedItme\RequestedItmeRules\RequestedItmeRules;
use Illuminate\Support\Facades\URL;
use App\Services\FileService;
use Illuminate\Support\Str;
use App\Http\Controllers\Item\ItemController;
class RequestedItmeController extends Controller
{
    public function index(){
        $RequestItem=RequestItemFolder::where('state',-1)->with(['storeRequest'=>function($query){
            $query->with(['item'=>function($query){
                $query->with('item')->get();
            }])->get();
        }])->orderBy('created_at', 'desc')->paginate(200);
        return $this->SuccessResponse($RequestItem,200);
    }
    public function ApproveRequest($id){
        $RequestItemFolder=RequestItemFolder::findOrfail($id);
        if($RequestItemFolder->state==-1){
        $RequestItemFolder->state=1;
        $RequestItemFolder->save();
        $items=storeRequest::where('folder_id',$RequestItemFolder->id)->get();
        foreach($items as $item){
            $request=new Request;
            $request->merge(['amount'=>$item->amount,'to'=>1]);
            (new ItemController)->transfer($request,$item->item_id);
        }
        return 'success';
    }
    return 'success';
    }
}

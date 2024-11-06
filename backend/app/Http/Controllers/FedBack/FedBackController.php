<?php

namespace App\Http\Controllers\FedBack;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FedBack;
use Illuminate\Support\Carbon;
use App\Http\Controllers\FedBack\FedBackRules\FedBackRules;
use App\Services\FileService;
use Illuminate\Support\Facades\URL;
class FedBackController extends Controller
{
    public function index(){
        $fedBack=FedBack::All();
        foreach($fedBack as $fed_back){
            if($fed_back->image){
            $fed_back->image=URL::to('storage/uploads/images/'.$fed_back->image);
            }
        }
        return $this->SuccessResponse($fedBack,200);
    }
    public function store(Request $request){

        $this->validate($request,FedBackRules::FedBackStoreRule);
        $file_name='';
        return $this->SuccessResponse('aman',404);

        if(filled($request->attachment)){
        if(!FileService::ExtensionTester($request->attachment,FileService::IMAGE)){
            return  $this->ErrorResponse(['attachment'=>['Unsuported Image Format']],422);
         }
         $file_name=FileService::ImageProccessore($request->attachment,FileService::IMAGE,'image')['filename'];
        }
         $fedBack=(new FedBack)->StoreFedBack($request,$file_name);
        return $this->SuccessResponse($fedBack,200);
    }
    public function update(Request $request,$id){
        $fedBack=FedBack::findOrfail($id);
        $this->validate($request,FedBackRules::FedBackUpdateRule($fedBack->id));
        $file_name='';
        if(filled($request->attachment)){
            if(!FileService::ExtensionTester($request->attachment,FileService::IMAGE)){
            return  $this->ErrorResponse(['attachment'=>['Unsuported Image Format']],422);
            }
            $file_name=FileService::ImageProccessore($request->attachment,FileService::IMAGE,'image')['filename'];
        }
        $fed_back=(new FedBack)->UpdateFedBack($request,$fedBack,FedBack::UpdateFedBack);
        $fed_back->image=URL::to('storage/uploads/images/'.$fed_back->image);
        return $this->successResponse($fed_back,200);
    }
    public function destroy($id){
        $fedBack=FedBack::findOrfail($id);
        $fedBack->delete();
        return $this->SuccessResponse(['message'=>['Successfully deleted']],200);
    }
}

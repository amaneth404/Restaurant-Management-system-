<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\bank;
use App\Models\branch;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Bank\BankRules\BankRules;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
class BankController extends Controller
{
    public function index(){
        $bank=bank::where('state',1)->orderBy('created_at', 'desc')->paginate(200);
        return $this->SuccessResponse($bank,200);
    }
    public function Search(Request $request){
        $bank=bank::where('name','like','%'.$request->search.'%')->whereIn('state',[1,-1])->orderBy('created_at', 'desc')->paginate(200);
        return $this->SuccessResponse($bank,200);
    }
    public function store(Request $request){        
        $this->validate($request,BankRules::BankStoreRule);
         $bank=(new bank)->Storebank($request);
        return $this->SuccessResponse($bank,200);
    }
    public function update(Request $request,$id){
        $bank=bank::findOrfail($id);
        $this->validate($request,BankRules::BankUpdateRule($bank->id));
        $bank=(new bank)->Updatebank($request,$bank,bank::Updatebank);
        return $this->successResponse($bank,200);
    }
}

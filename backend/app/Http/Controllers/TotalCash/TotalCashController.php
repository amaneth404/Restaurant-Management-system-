<?php

namespace App\Http\Controllers\TotalCash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TotalCash;
use App\Http\Controllers\TotalCash\TotalCashRules\TotalCashRules;
class TotalCashController extends Controller
{
    public function index(){
        $TotalCash=TotalCash::orderBy('created_at', 'desc')->paginate(200);
        return $this->SuccessResponse($TotalCash,200);
    }
    public function Search(Request $request){
        $TotalCash=TotalCash::where('name','like','%'.$request->search.'%')->whereIn('state',[1,-1])->orderBy('created_at', 'desc')->paginate(200);
        return $this->SuccessResponse($TotalCash,200);
    }
    public function store(Request $request){        
        $this->validate($request,TotalCashRules::TotalCashStoreRule);
         $TotalCash=(new TotalCash)->StoreTotalCash($request);
        return $this->SuccessResponse($TotalCash,200);
    }
    public function update(Request $request,$id){
        $TotalCash=TotalCash::findOrfail($id);
        $this->validate($request,TotalCashRules::TotalCashUpdateRule($TotalCash->id));
        $TotalCash=(new TotalCash)->UpdateTotalCash($request,$TotalCash,TotalCash::UpdateTotalCash);
        return $this->successResponse($TotalCash,200);
    }
    public function destroy($id){
        $Expense=TotalCash::findOrfail($id);
      $Expense->forceDelete();
        return $this->SuccessResponse(['message'=>['Successfully deleted']],200);
    }
}

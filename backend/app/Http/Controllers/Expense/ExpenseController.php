<?php

namespace App\Http\Controllers\Expense;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\CategoryExpense;

use App\Models\itemGroup;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Expense\ExpenseRules\ExpenseRules;
use Illuminate\Support\Facades\URL;
use App\Services\FileService;
class ExpenseController extends Controller
{
    public function index(){
        $Expense=Expense::where('isreported',0)->with('expense')->orderBy('created_at', 'desc')->paginate(50);
        return $this->SuccessResponse($Expense,200);
    }
    public function indexFilter($id){
        $Expense=Expense::where('isreported',0)->where('description',$id)->with('expense')->orderBy('created_at', 'desc')->paginate(50);
        return $this->SuccessResponse($Expense,200);
    }
    public function store(Request $request){
        $this->validate($request,ExpenseRules::ExpenseStoreRule);
         $Expense=(new Expense)->StoreExpense($request);
         if($request->description)
         $Expense['expense']=CategoryExpense::findOrfail($request->description);
        return $this->SuccessResponse($Expense,200);
    }
    public function update(Request $request){
        $Expense=Expense::findOrfail($request->id);
        $this->validate($request,ExpenseRules::ExpenseUpdateRule($Expense->id));
     
        $expense=(new Expense)->UpdateExpense($request,$Expense,Expense::UpdateExpense);
        if($request->description)
        $Expense['expense']=CategoryExpense::findOrfail($request->description);

        return $this->successResponse($expense,200);
    }
    public function destroy($id){
        $Expense=Expense::findOrfail($id);
      $Expense->forceDelete();
        return $this->SuccessResponse(['message'=>['Successfully deleted']],200);
    }
}

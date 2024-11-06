<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
class Expense extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable=[
        'description',
        'title',
        'amount',
        'created_at',
        'id',
        'deleted_at'
    ];
    public function expense(){
        return $this->belongsTo('App\Models\CategoryExpense','description');
    }
    const UpdateExpense=['description','amount','title'];
    public function StoreExpense($request){
        $request->merge(['id'=>Str::uuid()]);
        $request->merge(['title'=>$request->title]);
        if($request->date)
        $request->merge(['created_at'=>Carbon::parse($request->date)]);
        $expense=new Expense;
        return $expense->create($request->all());
    }
    public function UpdateExpense($request,$expense,$update_values){
        foreach($update_values as $value){
            $expense->$value=$request->$value;
        }
        if($request->date)
        $expense->created_at=Carbon::parse($request->date);
        $expense->save();
        return $expense;
    }
}

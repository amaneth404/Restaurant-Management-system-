<?php

namespace App\Http\Controllers\Casher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Casher;
use App\Models\User;
use App\Models\Credit;
use App\Models\reporttr;
use App\Models\Order;
use App\Models\item;
use App\Models\storeRequest;
use App\Models\RequestItemFolder;
use App\Http\Controllers\Item\ItemController;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Casher\CasherRules\CasherRules;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\bank;
use App\Services\FileService;
use Illuminate\Support\Facades\DB;
class CasherController extends Controller
{
    public function index($type=0){
      
        // return [$user_id,'aman'];      
        if($type==0){
            $date=Carbon::now()->addDay(1);
            $after=Carbon::parse(explode(' ',$date)[0].' 00:00');
            $now=Carbon::parse(explode(' ',$after)[0].' 00:00')->subDays(1);
            $Casher=Casher::where(function($query)  {
                $query->whereIN('state', [1,-1])
                      ->orWhere([['state','=',0],['isflaged','=',1]]);
            })->where('created_at','>=',$now)->with('waiter')->select('waiter as waiter','folder_id as folder_id',DB::raw('count(*) as total'),DB::raw('0 as number'),DB::raw('sum(order_price) as order_price'),DB::raw('Max(isdelivery) as isdelivery'),DB::raw('Max(isflaged) as isflaged'),DB::raw('Max(state) as state'),DB::raw('Max(fee) as fee'),DB::raw('Max(phone_number) as phone_number'),DB::raw('Max(table_name) as table_name'))->orderBy('created_at','desc')->groupBy('waiter','folder_id')->paginate(200);
        }else if($type==1){
            $date=Carbon::now()->addDay(1);
            $after=Carbon::parse(explode(' ',$date)[0].' 00:00');
            $now=Carbon::parse(explode(' ',$after)[0].' 00:00')->subDays(1);
            $Casher=Casher::whereIn('state',[1,-1])->where('created_at','>=',$now)->where('isflaged',1)->with('waiter')->select('waiter as waiter','folder_id as folder_id',DB::raw('count(*) as total'),DB::raw('0 as number'),DB::raw('sum(order_price) as order_price'),DB::raw('Max(isdelivery) as isdelivery'),DB::raw('Max(isflaged) as isflaged'),DB::raw('Max(state) as state'),DB::raw('Max(fee) as fee'),DB::raw('Max(phone_number) as phone_number'),DB::raw('Max(table_name) as table_name'))->orderBy('created_at')->groupBy('waiter','folder_id')->paginate(200);
        }else if($type==2){
            $date=Carbon::now()->addDay(1);
            $after=Carbon::parse(explode(' ',$date)[0].' 00:00');
            $now=Carbon::parse(explode(' ',$after)[0].' 00:00')->subDays(1);
            $Casher=Casher::where('created_at','>=',$now)->where('state',0)->where('isflaged',0)->with('waiter')->select('waiter as waiter','folder_id as folder_id',DB::raw('count(*) as total'),DB::raw('0 as number'),DB::raw('sum(order_price) as order_price'),DB::raw('Max(isdelivery) as isdelivery'),DB::raw('Max(fee) as fee'),DB::raw('Max(isflaged) as isflaged'),DB::raw('Max(state) as state'),DB::raw('Max(phone_number) as phone_number'),DB::raw('Max(table_name) as table_name'))->orderBy('created_at')->groupBy('waiter','folder_id')->paginate(200);
        }else if($type==3){
             $date=Carbon::now()->addDay(1);
            $after=Carbon::parse(explode(' ',$date)[0].' 00:00');
            $now=Carbon::parse(explode(' ',$after)[0].' 00:00')->subDays(1);
            $Casher=Casher::whereIn('state',[1,-1])->where('created_at','>=',$now)->where('isflaged',0)->where('isdelivery',1)->with('waiter')->select('waiter as waiter','folder_id as folder_id',DB::raw('count(*) as total'),DB::raw('0 as number'),DB::raw('sum(order_price) as order_price'),DB::raw('Max(isdelivery) as isdelivery'),DB::raw('Max(isflaged) as isflaged'),DB::raw('Max(state) as state'),DB::raw('Max(fee) as fee'),DB::raw('Max(phone_number) as phone_number'),DB::raw('Max(table_name) as table_name'))->orderBy('created_at')->groupBy('waiter','folder_id')->paginate(200);
        }else if($type==4){
            $date=Carbon::now()->addDay(1);
            $after=Carbon::parse(explode(' ',$date)[0].' 00:00');
            $now=Carbon::parse(explode(' ',$after)[0].' 00:00')->subDays(1);
            $Casher=Casher::where('created_at','>=',$now)->where('state',0)->where('isflaged',0)->where('isdelivery',1)->with('waiter')->select('waiter as waiter','folder_id as folder_id',DB::raw('count(*) as total'),DB::raw('0 as number'),DB::raw('sum(order_price) as order_price'),DB::raw('Max(isdelivery) as isdelivery'),DB::raw('Max(fee) as fee'),DB::raw('Max(isflaged) as isflaged'),DB::raw('Max(state) as state'),DB::raw('Max(phone_number) as phone_number'),DB::raw('Max(table_name) as table_name'))->orderBy('created_at')->groupBy('waiter','folder_id')->paginate(200);
         }

        return $this->SuccessResponse($Casher,200);
    }
    public function GroupedOrder(){
        $date=Carbon::now()->addDay(1);
        $after=Carbon::parse(explode(' ',$date)[0].' 00:00');
        $now=Carbon::parse(explode(' ',$after)[0].' 00:00')->subDays(1);
        $Casher = Casher::where('isdone', 0)
        ->where('tokichen', 1)
        ->where('created_at', '>=', $now)
        ->where('isflaged', 0)
        ->with('menu','waiter')
        ->select('orders','waiter','folder_id', DB::raw('count(*) as total'),DB::raw('max(remark) as remark')) // Group and concatenate ids
        ->groupBy('orders','folder_id','waiter')
        ->orderBy('created_at')
        ->get();
         return $Casher;
    }
    public function GroupedOrderDone(){
        $date=Carbon::now()->addDay(1);
        $after=Carbon::parse(explode(' ',$date)[0].' 00:00');
        $now=Carbon::parse(explode(' ',$after)[0].' 00:00')->subDays(1);
        $Casher = Casher::where('isdone', 1)
        ->where('tokichen', 1)
        ->where('created_at', '>=', $now)
        ->where('isflaged', 0)
        ->with('menu','waiter')
        ->select('orders','waiter','folder_id', DB::raw('count(*) as total'),DB::raw('max(remark) as remark')) // Group and concatenate ids
        ->groupBy('orders','folder_id','waiter')
        ->orderBy('done_time')
        ->get();
         return $Casher;
    }
    public function Chefeindex(){
            $date=Carbon::now()->addDay(1);
            $after=Carbon::parse(explode(' ',$date)[0].' 00:00');
            $now=Carbon::parse(explode(' ',$after)[0].' 00:00')->subDays(1);
            $Casher=Casher::where('isdone',0)->where('tokichen',1)->where('created_at','>=',$now)->with('menu')->where('isflaged',0)->with('waiter')->orderBy('created_at')->get();
            return $Casher->last();
            $last_id=0;
            if(count($Casher)){
            $last_id = $Casher->last()->created_at;
            }
            return $this->SuccessResponse($last_id,200);
    }
    public function Chefeindexadmin(){
        $date = Carbon::now()->addDay(1);
        $after = Carbon::parse(explode(' ', $date)[0] . ' 00:00');
        $now = Carbon::parse(explode(' ', $after)[0] . ' 00:00')->subDays(2);
        
        $Casher = Casher::where('isdone', 1)
            ->where('tokichen', 1)
            ->where('created_at', '>=', $now)
            ->where('isflaged', 0)
            ->with(['menu', 'waiter'])
            ->select(
                'orders',
                'waiter',
                'folder_id', 
                DB::raw('count(*) as total'), 
                DB::raw('max(remark) as remark'), 
                DB::raw('MAX(created_at) as last_created_at'), 
                DB::raw('MAX(done_time) as last_done_time'),
                DB::raw("TIMESTAMPDIFF(MINUTE, MAX(created_at), MAX(done_time)) AS time_diff") // Corrected TIMESTAMPDIFF usage
            )
            ->groupBy('orders', 'folder_id', 'waiter')
            ->orderBy('last_created_at')
            ->paginate(100);
        return $this->SuccessResponse($Casher,200);
    }
    public function ChefDone($id){
        $Order=Casher::findOrfail($id);
        $Order->done_time=Carbon::now();
        $Order->isdone=1;
        $Order->save();
        return $Order;
    }
    public function ChefDoneAll($id){
        $Casher=Casher::where('folder_id',$id)->update(['isdone'=>1,'done_time'=>Carbon::now()]);
    }
    public function PayedOrders(){
        $Casher=Casher::where('state',0)->where('isdelivery',0)->orderBy('created_at', 'desc')->with('waiter')->get();
        return $this->SuccessResponse($Casher,200);
    }
    public function FlagedOrdersAll(){
        $Casher=Casher::where('isflaged',1)->where('isdelivery',0)->orderBy('created_at', 'desc')->with('waiter')->get();
        return $this->SuccessResponse($Casher,200);
    }
    public function deliveryPayedOrders(){
        $Casher=Casher::where('state',0)->where('isdelivery',1)->orderBy('created_at', 'desc')->with('waiter')->get();
        return $this->SuccessResponse($Casher,200);
    }
    public function deliveryFlagedOrdersAll(){
        $Casher=Casher::where('isflaged',1)->where('isdelivery',1)->orderBy('created_at', 'desc')->with('waiter')->get();
        return $this->SuccessResponse($Casher,200);
    }
    public function UpdateBank(Request $request){
        $today=Carbon::today();
        $Cashers=Casher::where('waiter',$request->user_id)->where('created_at','>=',$today)->where('state','=',0)->wherenull('bank')->where('given','=',0)->first();
        $Cashers->bank=$request->bank_id;
        $Cashers->bank_money=$request->money;
        $Cashers->save();
        return $this->SuccessResponse('success',200);
    }
    public function Payed(Request $request,$id){
        Casher::where('folder_id',$id)->where('state','!=',0)->update(['state'=>0]);
        if($request->bank){
        $Casher=Casher::where('folder_id',$id)->where('state',0)->first();
        $Casher->bank=$request->bank;
        $Casher->bank_money=$request->bank_money;
        $Casher->save();
        }
       
        return $this->SuccessResponse('success',200);
    }
    public function getCasherReport(){
        $cashers=Casher::select('aut_id',DB::raw('sum(price) as price'))->where('state',0)->where('isflaged',0)->where('given',0)->with('User')->wherenotNull('aut_id')->groupBy('aut_id')->get();
        foreach($cashers as $casher){
            $casher['credit']=Casher::where('aut_id',$casher->aut_id)->where('state',0)->where('isflaged',0)->where('given',0)->where('credit','!=','0')->sum('price');
            $casher['gift']=Casher::where('state',0)->where('is_gift',1)->where('given',0)->where('isflaged',0)->where('w_given',0)->where('aut_id',$casher->aut_id)->sum('price');
            $casher['bank']=Casher::where('aut_id',$casher->aut_id)->where('state',0)->where('isflaged',0)->where('given',0)->wherenotnull('bank')->sum('bank_money');
            $casher['paid']=Credit::where('casher',$casher->aut_id)->where('state',1)->sum('paid');
        }
        return $cashers;
    }
    public function Given($id){
        Casher::where('aut_id',$id)->where('given',0)->update(['given'=>1]);
        Credit::where('casher',$id)->where('state',1)->update(['state'=>2]);
    }
    public function getWaiterReport(){
        $cashers=Casher::select('waiter',DB::raw('sum(price) as price'))->where('state',0)->where('isflaged',0)->where('given',0)->where('w_given',0)->with('waiter')->wherenotNull('waiter')->groupBy('waiter')->get();
        foreach($cashers as $casher){
            $casher['credit']=Casher::where('waiter',$casher->waiter)->where('state',0)->where('isflaged',0)->where('given',0)->where('credit','!=','0')->sum('price');
            $casher['gift']=Casher::where('state',0)->where('is_gift',1)->where('isflaged',0)->where('given',0)->where('w_given',0)->where('waiter',$casher->waiter)->sum('price');
            $casher['bank']=Casher::where('waiter',$casher->waiter)->where('isflaged',0)->where('state',0)->where('given',0)->wherenotnull('bank')->sum('bank_money');
        }
        return $cashers;
    }
    public function WaiterGiven($id,$amount){
        
        Casher::where('waiter',$id)->where('w_given',0)->update(['w_given'=>1]);
        $user=User::findOrfail($id);
        $user->credit+=$amount;
        $user->save();
    }
    

    public function FlagedOrders(){
        $Casher=Casher::whereIn('state',[1,-1])->where('isflaged',1)->with('menu')->with('waiter')->select('orders as orders',DB::raw('count(*) as total'),DB::raw('0 as number'),DB::raw('sum(price) as price'),DB::raw('Max(waiter) as waiter'))->groupBy('orders')->get();

        // $Casher=Casher::whereIn('state',[1,-1])->where('isflaged',1)->with('waiter')->orderBy('created_at', 'desc')->paginate(100);
        return $this->SuccessResponse($Casher,200);
    }
    public function CasherWithOrder($id){
        
        $Casher=Casher::where('folder_id',$id)->select('waiter as waiter','orders as orders',DB::raw('count(*) as total'),DB::raw('max(order_price) as order_price'),DB::raw('max(created_at) as created_at'),DB::raw('max(bank_money) as bank_money'),DB::raw('max(index_holder) as index_holder'),DB::raw('sum(order_price) as order_price'),DB::raw('max(folder_id) as folder_id'),DB::raw('max(table_name) as table_name'),DB::raw('max(phone_number) as phone_number'),DB::raw('max(fee) as fee'),DB::raw('max(isdelivery) as isdelivery'),DB::raw('max(isflaged) as isflaged'),DB::raw('max(state) as state'))->with('waiter','menu')->orderBy('created_at', 'desc')->groupBy('waiter','orders')->get();
       $price=0;
       foreach($Casher as $cash){
        $price+=$cash->order_price;
       }
       if($Casher){
        $Casher[0]['total_price']=$price;
       }
        return $this->SuccessResponse($Casher,200);
    }
    public function Search(Request $request){
        $date=Carbon::now()->addDay(1);
        $after=Carbon::parse(explode(' ',$date)[0].' 00:00');
        $now=Carbon::parse(explode(' ',$after)[0].' 00:00')->subDays(1);
        $Casher=Casher::where([['table_name','like',$request->search.'%','or'],['phone_number','like',$request->search.'%','or']])->whereIn('state',[1,-1])->where('credit',0)->where('is_gift',0)->where('created_at','>=',$now)->where('isflaged',0)->with('waiter')->select('waiter as waiter','folder_id as folder_id',DB::raw('count(*) as total'),DB::raw('0 as number'),DB::raw('sum(price) as price'),DB::raw('Max(isdelivery) as isdelivery'),DB::raw('Max(isflaged) as isflaged'),DB::raw('Max(state) as state'),DB::raw('Max(fee) as fee'),DB::raw('Max(phone_number) as phone_number'),DB::raw('Max(table_name) as table_name'))->orderBy('created_at')->groupBy('waiter','folder_id')->paginate(200);
      
        return $this->SuccessResponse($Casher,200);
    }
    public function store(Request $request){
        $this->validate($request,CasherRules::CasherStoreRule);
        if($request->isdelivery==0){
            $date=Carbon::now()->addDay(1);
            $after=Carbon::parse(explode(' ',$date)[0].' 00:00');
            $now=Carbon::parse(explode(' ',$after)[0].' 00:00')->subDays(1);
        $Casher=Casher::where('table_name',$request->table_name)->where('created_at','>=',$now)->where('isflaged',0)->where('state',1)->get();
        if(count($Casher)>0){
            return $this->ErrorResponse(['table_num'=>['table is alredy taken']],422);
        }
        }
        $all = Casher::select('folder_id')->groupBy('folder_id')->get()->count('folder_id');
        $index=1;
        $isfee_set=0;
        if($all)
        $index=($all%100)+1;
        $waiter=null;
        $order_holder=[];
        $store_order=[];
        $waiter=User::findOrfail($request->waiter);
        $counter=1;
        $folder_id=Str::uuid();
        $total_price=0;
        $isupdated_fee=0;
        foreach($request->order as $ord){
        $holder=[];
        $casher_h=null;
        $remark=$ord['remark'];
        $isstore=0;
            for($i=0;$i<$ord['order']*1;$i++){
            $Casher=new Casher;
        $Casher->id=Str::uuid();
        if($i==0){
            $Casher->remark=$remark;
        }
        $Casher->folder_id=$folder_id;
        $Casher->credit=$request->credit?$request->credit:0;
        $Casher->isdelivery=$request->isdelivery?$request->isdelivery:0;
        
        if($request->credit){
            $user=User::findOrfail($request->credit);
            $user->credit+=$ord['menu']['price'];
            $user->save();
        }
        $Casher->is_gift=$request->is_gift?1:0;
        $Casher->index_holder='shege/'.$index;
        $isupdated_fee='shege/'.$index;
        $Casher->orders= ($ord['menu']['menu_id']==-1?$ord['menu']['name']:$ord['menu']['menu_id']);
        $Casher->waiter=$request->waiter;
        $Casher->table_name=$request->table_name;
        $Casher->amount=1;
        $Casher->aut_id=$request->casher?$request->casher:Auth::user()->id;
        $Casher->state=1;
        if($request->isdelivery&&$isfee_set==0){
            $isfee_set=1;
            $Casher->phone_number=$request->phone_number;
            $Casher->fee=$request->fee;
            $Casher->price=$ord['menu']['price']+$request->fee;
            $Casher->order_price=$ord['menu']['price'];
            $total_price+=$ord['menu']['price']+$request->fee;
        }else{
        $Casher->price=$ord['menu']['price'];
        $Casher->order_price=$ord['menu']['price'];
        $total_price+=$ord['menu']['price'];
        }
        $Casher->tokichen=$ord['menu']['tokichen'];
        $Casher->save();
        $counter++;
            if($ord['menu']['menu_id']==-1){
                $Casher->menu;
                // $order_holder[]= $Casher;
            }else{
                $Casher->menu;
                if($ord['menu']['item_id']){
                    // $ord['order']=1;
                    $isstore=1;
                    $casher_h=$Casher;
                    $this->RequestToStore($casher_h->id,[$ord]);
                    $holder[]=$ord;
                   
                }
            }
        }
        if($isstore){
            $store_order[]=$ord;
        }else{
            $order_holder[]=$ord;
        }
        

        }
        
        $data_holder['index_holder']=$isupdated_fee;
        $data_holder['waiter']=$waiter;
        $data_holder['order']=$order_holder;
        $data_holder['store']=$store_order;
        $data_holder['isdelivery']=$request->isdelivery?$request->isdelivery:0;
        $data_holder['price']=$total_price;
        $data_holder['table_name']=$request->table_name;
        $data_holder['created_at']=Carbon::now();
        if($request->isdelivery)
        $data_holder['phone_number']=$request->phone_number;
        return $this->SuccessResponse($data_holder,200);
    }
    private function RequestToStore($id,$request){
        $RequestItemFolder=RequestItemFolder::where('state',-1)->where('chaser_id',$id)->first();
        if($RequestItemFolder){
            foreach($request as $req){
            $storeRequest=new storeRequest;
            $storeRequest->id=Str::uuid();
            $storeRequest->item_id=$req['menu']['item_id'];
            $storeRequest->amount=1;
            $storeRequest->folder_id=$RequestItemFolder->id;
            $storeRequest->save();
            $request=new Request;
            $request->merge(['amount'=>$storeRequest->amount,'to'=>1]);
            (new ItemController)->transfer($request,$storeRequest->item_id);
            }
        }else{
            $RequestItemFolder=new RequestItemFolder;
            $RequestItemFolder->id=Str::uuid();
            $RequestItemFolder->state=1;
            $RequestItemFolder->chaser_id=$id;
            $RequestItemFolder->save();
            foreach($request as $req){
            $storeRequest=new storeRequest;
            $storeRequest->id=Str::uuid();
            $storeRequest->item_id=$req['menu']['item_id'];
            $storeRequest->amount=1;
            $storeRequest->folder_id=$RequestItemFolder->id;
            $storeRequest->save();
            $request=new Request;
            $request->merge(['amount'=>1,'to'=>1]);
            (new ItemController)->transfer($request,$storeRequest->item_id);
            }
        }
        return 'success';
    }
    public function cahgeOrderConfig(Request $request){
        if($request->isdelivery==0){
            $date=Carbon::now()->addDay(1);
            $after=Carbon::parse(explode(' ',$date)[0].' 00:00');
            $now=Carbon::parse(explode(' ',$after)[0].' 00:00')->subDays(1);
        $Casher=Casher::where('table_name',$request->table_name)->where('created_at','>=',$now)->where('isflaged',0)->where('state',1)->get();
        if(count($Casher)>0){
            return $this->ErrorResponse(['table_name'=>['table is alredy taken']],422);
        }
        }
        $isdelivery=$request->isdelivery?$request->isdelivery:0;
        $phone_number=$request->phone_number?$request->phone_number:null;
        $fee=$request->fee?$request->fee:0;
        $waiter=$request->waiter?$request->waiter:null;
        $table_name=$request->table_name?$request->table_name:null;
        if($isdelivery){
            $table_name=null;
        }
        Casher::where('folder_id',$request->folder_id)->where('state',1)->update(['isdelivery'=>$isdelivery,'waiter'=>$waiter,'table_name'=>$table_name]);
        
        if($request->old_isdelivery){
            $casher_holder=Casher::where('folder_id',$request->folder_id)->where('state',1)->where('fee','>',0)->first();
            if($isdelivery==0){
                $casher_holder->fee=0;
                $casher_holder->price-=$casher_holder->fee;
                $casher_holder->phone_number=null;
            
            }else{
                $casher_holder->fee=$fee;
                $casher_holder->price+=$fee;
                $casher_holder->phone_number=$phone_number;
            }
            $casher_holder->save();
        }else{
            $casher_holder=Casher::where('folder_id',$request->folder_id)->where('state',1)->first();
            $casher_holder->fee=$fee;
            $casher_holder->price+=$fee;
            $casher_holder->phone_number=$phone_number;
            $casher_holder->save();
        }
        if($request->credit){
            $user=User::findOrfail($request->credit);
            $user->credit+=$request->total_price;
            $user->save();
        }
        
    }
    public function update(Request $request){
        $Casher_old=Casher::where('folder_id',$request->id)->first();
        $this->validate($request,CasherRules::CasherUpdateRule($Casher_old->id));

        $this->validate($request,CasherRules::CasherStoreRule);
       
        $index=$Casher_old->index_holder;
        $waiter=null;
        $order_holder=[];
        $store_order=[];
        $waiter=User::findOrfail($request->waiter);
        $counter=1;
        $folder_id=$request->id;
        $total_price=0;
        $isupdated_fee=0;
        foreach($request->order as $ord){
        $holder=[];
        $casher_h=null;
        $remark=$ord['remark'];
        $isstore=0;
            for($i=0;$i<$ord['order']*1;$i++){
            $Casher=new Casher;
        $Casher->id=Str::uuid();
        if($i==0){
            $Casher->remark=$remark;
        }
        $Casher->folder_id=$folder_id;
        $Casher->credit=$request->credit?$request->credit:0;
        $Casher->isdelivery=$request->isdelivery?$request->isdelivery:0;
        
        if($request->credit){
            $user=User::findOrfail($request->credit);
            $user->credit+=$ord['menu']['price'];
            $user->save();
        }
        $Casher->is_gift=$request->is_gift?1:0;
        $Casher->index_holder=$index;
        $isupdated_fee=$index;
        $Casher->orders= ($ord['menu']['menu_id']==-1?$ord['menu']['name']:$ord['menu']['menu_id']);
        $Casher->waiter=$request->waiter;
        $Casher->table_name=$request->table_name;
        $Casher->amount=1;
        $Casher->aut_id=$request->casher?$request->casher:Auth::user()->id;
        $Casher->state=1;
        if($request->isdelivery){
            $Casher->phone_number=$request->phone_number;
            $Casher->fee=$request->fee;
            $Casher->price=$ord['menu']['price']+$request->fee;
            $Casher->order_price=$ord['menu']['price'];
            $total_price+=$ord['menu']['price']+$request->fee;
        }else{
        $Casher->price=$ord['menu']['price'];
        $Casher->order_price=$ord['menu']['price'];
        $total_price+=$ord['menu']['price'];
        }
        $Casher->tokichen=$ord['menu']['tokichen'];
        $Casher->save();
        $counter++;
            if($ord['menu']['menu_id']==-1){
                $Casher->menu;
                $order_holder[]= $Casher;
            }else{
                $Casher->menu;
                if($ord['menu']['item_id']){
                    // $ord['order']=1;
                    $isstore=1;
                    $casher_h=$Casher;
                    $this->RequestToStore($casher_h->id,[$ord]);
                    $holder[]=$ord;
                   
                }
            }
        }
        if($isstore){
            $store_order[]=$ord;
        }else{
            $order_holder[]=$ord;
        }
        

        }
        
        $data_holder['index_holder']=$isupdated_fee;
        $data_holder['waiter']=$waiter;
        $data_holder['order']=$order_holder;
        $data_holder['store']=$store_order;
        $data_holder['isdelivery']=$request->isdelivery?$request->isdelivery:0;
        $data_holder['price']=$total_price;
        $data_holder['table_name']=$request->table_name;
        if($request->isdelivery)
        $data_holder['phone_number']=$request->phone_number;
        return $this->SuccessResponse($data_holder,200);
        }
    public function destroy($id){
        $Casher=Casher::findOrfail($id);
        $Casher->delete();
        return $this->SuccessResponse(['message'=>['Successfully deleted']],200);
    }
    public function Flag($id){

        $Cashers=Casher::where('folder_id',$id)->where('credit',0)->where('is_gift',0)->wherenull('bank')->where('isflaged',0)->where('given',0)->get();
        foreach($Cashers as $Casher){
        $Casher->isflaged=1;
        $Casher->state=0;
        $Casher->save();
        $RequestItemFolder=RequestItemFolder::where('state',1)->where('chaser_id',$Casher->id)->first();
        if($RequestItemFolder){
            $storeRequest=storeRequest::where('folder_id',$RequestItemFolder->id)->first();
            $item=item::findOrfail($storeRequest->item_id);
            $item->sold-=$Casher->amount;
            $item->save();
            $reporttr=reporttr::where('items',$item->id)->where('amount',1)->where('type',1)->latest()->first();
            $reporttr->delete();
            $storeRequest->delete();
            $RequestItemFolder->delete();
        }
    

}
        return $this->SuccessResponse(['message'=>['Successfully deleted']],200);
    }
    public function UnFlag($id){
        $Casher=Casher::findOrfail($id);
        if($Casher->isflaged==1){
        $Casher->isflaged=0;
        $Casher->save();
        $RequestItemFolder=RequestItemFolder::where('state',0)->where('chaser_id',$id)->first();
        if($RequestItemFolder){
            $storeRequest=storeRequest::where('folder_id',$RequestItemFolder->id)->get();
            foreach($storeRequest as $req){
                $item=item::findOrfail($req->item_id);
                $item->sold+=$req->amount;
                $item->save();
                $reporttr=reporttr::where('items',$item->id)->where('amount',$req->amount)->where('type',1)->latest()->first();
                $reporttr->state=1;
                $reporttr->save();
            }
        }
    }
        return $this->SuccessResponse(['message'=>['Successfully deleted']],200);
    }
    public function deleteOrder($id){
        $Casher=Casher::findOrfail($id);
        $Casher->delete();
        
        return $this->SuccessResponse(['message'=>['Successfully deleted']],200);
    }
}

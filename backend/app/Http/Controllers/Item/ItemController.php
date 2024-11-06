<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\itemGroup;
use App\Models\item;
use App\Models\User;
use App\Models\reporttr;
use App\Models\report;
use App\Models\itemLogs;
use App\Models\branch;
use App\Models\menu;
use App\Models\category;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Item\ItemRules\ItemRules;
use App\Services\FileService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\RequestItemFolder;
use App\Models\RequestItem;
class ItemController extends Controller
{
    protected $_queryReport=[];
    public function index(){
        $Item=item::whereIn('state',[1,-1,4])->with('branch_data')->with('item')->orderBy('created_at', 'desc')->paginate(50);
        return $this->SuccessResponse($Item,200);
    }
    public function Link($id,$menu_id){
            $item=item::findorfail($id);
            $menu=menu::findorfail($menu_id);
            $menu->item_id=$id;
            $item->linked=1;
            $item->save();
            $menu->save();
    }
    public function UnLink($id){
        $item=item::findorfail($id);
        $item->linked=0;
        $item->save();
}
public function transferHistory()
{
    // Fetch the data grouped by the date part of 'created_at', sum the amount, and get related data
    $Item = reporttr::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as amount'), // Assuming 'amount' is the column to sum
                DB::raw('Max(item) as item'),
                DB::raw('Max(branch) as branch'), // Concatenate item ids for each group
                DB::raw('Sum(price) as price'), // Concatenate item ids for each group
                DB::raw('Sum(sell_price) as sell_price'), // Concatenate item ids for each group
                DB::raw('Max(type) as type') // Concatenate item ids for each group
            )
            ->where('state', 1)
            ->where('isreported', 0)
            ->with(['branch_data', 'item']) // Get relationships
            ->groupBy(DB::raw('DATE(created_at)'),'type','item') // Group by date (ignoring time)
            ->orderBy('created_at', 'desc')
            ->paginate(200);

    return $this->SuccessResponse($Item, 200);
}
    public function transferAllHistory(){
        $Item=reporttr::where('state',1)->where('isreported','0')->with('branch_data')->with('item')->orderBy('created_at', 'desc')->get();
        return $this->SuccessResponse($Item,200);
    }
    public function transfer(Request $request,$id){
        $item=item::findOrfail($id);
        $amount=$request->amount;
            if($item){
                if(($item->amount*1+$item->sold*1)<$amount){
                    return $this->ErrorResponse('amount is greter than the real amount !!',422);
                }
                $request->merge([
                    'sell_price'=>$item->sell_price,
                    'price'=>$item->price,
                    'item'=>$item->item,
                    'category'=>$item->category,
                    'amount'=>$amount,
                    'branch'=>'1',
                    'type'=>$request->to
                ]);
                $itemGroup=itemGroup::findOrfail($item->item);
                $item->sold+=$amount*1;
                $item->save();
                $item->amount=$amount;
                $item->branch='1';
                $item->items=$item->id;
                $item->id=Str::uuid();
                $data=json_encode($item);
                $data=json_decode($data,true);
                $data['type']=$request->to*1;
                (new reporttr)->Storereporttr($data);
                $items['branch_data']='1';
                $items['item']=$itemGroup;
                return $this->successResponse($items,200);
            }else{
                return $this->ErrorResponse('Item Dose not exist',404);
            }
    }
    public function UpdateRequestName(Request $request,$id){
        $RequestItemFolder=RequestItemFolder::findOrfail($id);
        $RequestItemFolder->name=$request->name;
        $RequestItemFolder->save();
    }
    public function indexUser(){
        $branch=branch::where('user',Auth::user()->id)->where('state',1)->first();
        $Item=item::whereIn('state',[1,-1,4])->where('branch',$branch->id)->with('branch_data')->with('item')->orderBy('created_at', 'desc')->paginate(200);
        return $this->SuccessResponse($Item,200);
    }
    public function indexAdmin($ismain){
        $userid=false;
        if($ismain==1){
            $branch=branch::where('user',Auth::user()->id)->first();
            $userid=$branch->id;
        }

        $Item=item::whereIn('state',[1,-1,4])->with('branch_data')->with('item') ->when($userid, function ($query, $userid) {
            return $query->where('branch', $userid);
        })->orderBy('created_at', 'desc')->paginate(200);
        return $this->SuccessResponse($Item,200);
    }
    public function indexItemLog(){
        $Item=itemLogs::where('state',1)->where('isreported','0')->with('branch_data')->with('item')->orderBy('created_at', 'desc')->paginate(200);
        return $this->SuccessResponse($Item,200);
    }
    public function indexItemLogAll(){
        $Item=itemLogs::where('state',1)->where('isreported','0')->with('branch_data')->with('item')->orderBy('created_at', 'desc')->get();
        return $this->SuccessResponse($Item,200);
    }
    public function indexItemLogAllReport(Request $request,$id){
        $x=1;
        $now='';
        $after='';
        if(!filled($request->from)){
        if($id==1){
            $x=1;
        }else if($id==2){
            $x=7;
        }else if($id==3){
            $x=30;
        }else if($id==4){
            $x=365;
        }
        $date=Carbon::now()->addDay(1);
        $after=Carbon::parse(explode(' ',$date)[0].' 00:00');
        $now=Carbon::parse(explode(' ',$now)[0].' 00:00')->subDays($x);
    }else{
        $now=Carbon::parse($request->from);
        $after=Carbon::parse($request->to);
    }
    $branch=branch::where('user',Auth::user()->id)->first();
    $queryReport = [
        ['state','=', 1],
        ['isreported','=', '0'],
        ['created_at','>',$now],
        ['created_at','<',$after]
    ];
    if ($request->isreported) {
        $queryReport = [
            ['state','=', 1],
            ['created_at','>',$now],
            ['created_at','<',$after]
        ];
        if ($request->reported_date) {
            $queryReport[] =['isreported','=', $request->reported_date];
        }
    }
    if ($request->ismain) {
        $queryReport[]= ['branch','=',$branch->id];
    }
        $Item=itemLogs::where($queryReport)->with('branch_data')->with('item')->orderBy('created_at', 'desc')->get();
        return $this->SuccessResponse($Item,200);
    }
    public function indexItemInStoreAllReport(Request $request,$id){    
        $Item=item::where('type',1)->with('branch_data')->with('item')->orderBy('created_at', 'desc')->get();
        return $this->SuccessResponse($Item,200);
    }
    public function indexItemInShopStoreAllReport(Request $request,$id){
        $Item=item::with('branch_data')->with('item')->orderBy('created_at', 'desc')->get();
        return $this->SuccessResponse($Item,200);
    }
    public function ItemInBranch(Request $request,$id){
        $x=1;
        $now='';
        $after='';
        if(!filled($request->from)){
        if($id==1){
            $x=1;
        }else if($id==2){
            $x=7;
        }else if($id==3){
            $x=30;
        }else if($id==4){
            $x=365;
        }
        $date=Carbon::now()->addDay(1);
        $after=Carbon::parse(explode(' ',$date)[0].' 00:00');
        $now=Carbon::parse(explode(' ',$after)[0].' 00:00')->subDays($x);
    }else{
        $to = explode('/',$request->to);

        $to = $to[1] . '/' . $to[0] . '/' .$to[2];
        $from = explode('/',$request->from);
        $from = $from[1] . '/' . $from[0] . '/' . $from[2];

        $now=Carbon::parse($from);
        $after=Carbon::parse($to);
    }
    $branch=branch::where('user',Auth::user()->id)->first();
    $this->_queryReport = [
        ['state',1],
        ['created_at','>',$now],
        ['created_at','<',$after]
    ];
    if (filled($request->unique_id)) {
        $isreported = $request->unique_id ? $request->unique_id : 0;
        $this->_queryReport = [
            ['state', 1],
        ['isreported', $isreported],
            ['created_at','>',$now],
            ['created_at','<',$after]
        ];
    }
        $Item=item::where('state',1)->where('type',0)->with('branch_data')->orderBy('created_at', 'desc')->
        select('branch',
        DB::raw('SUM(amount - sold) as inStock,branch'))->groupBy('branch')->get();
        for($i=0;$i<count($Item);$i++){
            $total=report::where('branch',$Item[$i]->branch)->where($this->_queryReport)->sum('amount');
            $Item[$i]['sold']=$total;
        }
       
        $holder = [];
    for ($i = 0; $i < count($Item); $i++) {
        $aman = report::where('branch',$Item[$i]->branch)->select(DB::raw('SUM(amount) as sold'),DB::raw('YEAR(created_at) year,MONTH(created_at) month'))->groupby(DB::raw('YEAR(created_at),MONTH(created_at)'))->get();
        $holder[]= ['report'=> $Item[$i], 'general'=> count($aman)>0 ? $aman : [] ];
    }
    $inStore= $Item=item::where('state',1)->where('type',1)->count();
    $holder['items_in_store']=$inStore;
       
        return $this->SuccessResponse($holder,200);
    }
    public function getTotalItemsByitem(Request $request,$branch,$id=null){
        $query = [];
        // if ($id) {
        //     $query []= [ 'item','=',$id];
        // }
        if (filled($request->from)) {
            $to = explode('/',$request->to);
            $to = $to[1] . '/' . $to[0] . '/' . $to[2];
            $from = explode('/',$request->from);
            $from = $from[1] . '/' . $from[0] . '/' . $from[2];
            $now=Carbon::parse($from);
            $after=Carbon::parse($to);
            $queryLookup = [
                ['created_at','>', $now],
                ['created_at','<', $after] 
            ];
        }
        $isreported = $request->unique_id ? $request->unique_id : 0;
        if ($isreported) {
            $queryLookup[]=['isreported','=', $isreported];
        }else{
            $queryLookup[]= ['isreported','=', '0'];
        }
        $this->_queryReport=$queryLookup;
        $Item=item::whereIn('state',[1,-1,4])->where('type',0)->with('category_data')->orderBy('created_at', 'desc')->
        select('category',
        DB::raw('SUM(amount - sold) as inStock'))->groupBy('category')->get();

        for($i=0;$i<count($Item);$i++){
            $data=report::where('category',$Item[$i]->category)->where($queryLookup)->select( DB::raw('SUM(amount) as sold'),DB::raw('SUM(amount*sell_price) as total_sell'),
            DB::raw('SUM(amount*(sell_price-price)) as total_revenu'))->first();
            $Item[$i]['sold']=$data->sold;
            $Item[$i]['total_sell']=$data->total_sell;
            $Item[$i]['total_revenu']=$data->total_revenu;

        }

        
        $Item_report=item::whereIn('state',[1,-1,4])->
        select(DB::raw('SUM(amount - sold) as inStock'))->first();

        $report_d=report::where($queryLookup)->select( DB::raw('SUM(amount) as sold'),
        DB::raw('SUM(amount*sell_price) as total_sell'),
        DB::raw('SUM(amount*(sell_price-price)) as total_revenu'))->first();

        $inStore= $Item=item::where('state',1)->where('type',1)->count();

        $Item_report['sold']=$report_d->sold;
        $Item_report['total_sell']=$report_d->total_sell;
        $Item_report['total_revenu']=$report_d->total_revenu;

        return $this->SuccessResponse(['total'=>$Item_report,'data'=>$Item,'items_in_store'=>$inStore],200);

    }
    public function store(Request $request){
        $branch='';
        $this->validate($request,ItemRules::ItemStoreRule);
        $item_group=itemGroup::findorfail($request->item);
        if(!$request->branch){
            $branch=branch::where('user',Auth::user()->id)->first();
        }else{
            $branch=branch::findorfail($request->branch);
        }
        if($branch){
            $request->merge([
                'branch'=>$branch->id,
                'category'=>$item_group->category
            ]);
        }else{
            return $this->ErrorResponse('Branch Dose not exist',404);
        }
        // $test=item::where('item',$item_group->id)->where('branch',$branch->id)->where('type',($request->type?1:0))->first();
        // if(!$test){
            $items=(new item)->StoreItem($request);
        // }else{
        //     $items=item::where('item',$item_group->id)->where('branch',$branch->id)->where('type',($request->type?1:0))->first();
        //     $items->amount=$items->amount*1+$request->amount*1;
        //     $items->sell_price=$request->sell_price;
        //     $items->price=$request->price;
        //     $items->branch=$request->branch;
        //     $items->save();
        // }
        (new itemLogs)->StoreItem($request);
         $items['branch_data']=$branch;
         $items['type']=($request->type?1:0);
         $items['item']=$item_group;
        return $this->SuccessResponse($items,200);
    }
    public function update(Request $request,$id){
        $Item=item::findOrfail($id);
        $this->validate($request,ItemRules::ItemUpdateRule($Item->id));
        $Item=(new item)->UpdateItem($request,$Item,Item::UpdateItem);
        return $this->successResponse($Item,200);
    }
    public function TransferFromStoreToMain(Request $request,$id,$amount){
            $item=item::findOrfail($id);
            $branch=branch::where('user',Auth::user()->id)->first();
            if($item){
                if(($item->amount*1+$item->sold*1)<$amount){
                    return $this->ErrorResponse('amount is greter than the real amount !!',422);
                }
                $request->merge([
                    'sell_price'=>$item->sell_price,
                    'price'=>$item->price,
                    'item'=>$item->item,
                    'category'=>$item->category,
                    'amount'=>$amount,
                    'branch'=>$item->branch,
                ]);
                $itemGroup=itemGroup::findOrfail($item->item);
                $test=item::where('item',$itemGroup->id)->where('branch',$branch->id)->where('type',0)->first();
                if(!$test){
                    $items=(new item)->StoreItem($request);
                }else{
                    $items=item::where('item',$itemGroup->id)->where('branch',$branch->id)->where('type',0)->first();
                    $items->amount=$items->amount*1+$amount*1;
                    $items->save();
                }
                $item->sold+=$amount*1;
                $item->save();
                $item->amount=$amount;
                $item->branch=$branch->id;
                $item->items=$item->id;
                $item->id=Str::uuid();
                $data=json_encode($item);
                $data=json_decode($data,true);
                (new reporttr)->Storereporttr($data);
                $items['branch_data']=$branch;
                $items['item']=$itemGroup;
                return $this->successResponse($items,200);
            }else{
                return $this->ErrorResponse('Item Dose not exist',404);
            }
    }
    public function destroy(Request $request,$id){
        $Item=item::findOrfail($id);
        $Item->state=0;
        $Item->save();
        return $this->successResponse('success',200);
    }
    public function DActivet(Request $request,$id){
        $Item=item::findOrfail($id);
        $Item->state=-1;
        $Item->save();
        return $this->successResponse('success',200);
    }
    public function Activet(Request $request,$id){
        $Item=item::findOrfail($id);
        $Item->state=1;
        $Item->save();
        return $this->successResponse('success',200);
    }
    public function FilterItems(Request $request,$id){
        $Item=item::whereIn('state',[1,-1,4])->where('item',$id)->with('branch_data')->with('item')->orderBy('created_at', 'desc')->paginate(200);
        $total_items=item::whereIn('state',[1,-1,4])->where('item',$id)->select(DB::raw('SUM(amount - sold) as amount'))->first();
        return $this->SuccessResponse(['items'=>$Item,'total'=>$total_items],200);
    }
    public function FilterItemsDashbored(Request $request,$id,$ismain=null){
        $query=[];
        if ($ismain == 1) {
            $branch = branch::where('user',Auth::user()->id)->where('state',1)->first();
            $query = [['branch','=',$branch->id]];
        }
        $Item=item::whereIn('state',[1,-1,4])->where('item',$id)->where($query)->with('branch_data')->with('item')->orderBy('created_at', 'desc')->paginate(200);
        return $this->SuccessResponse($Item,200);
    }
    public function FilterItemsLog($id){
        $Item=itemLogs::where('state',1)->where('item',$id)->where('isreported','0')->with('branch_data')->with('item')->orderBy('created_at', 'desc')->paginate(200);
        return $this->SuccessResponse($Item,200);
    }
    public function FilterTransfered($id){
         $Item = reporttr::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as amount'), // Assuming 'amount' is the column to sum
                DB::raw('Max(item) as item'),
                DB::raw('Max(branch) as branch'), // Concatenate item ids for each group
                DB::raw('Sum(price) as price'), // Concatenate item ids for each group
                DB::raw('Sum(sell_price) as sell_price'), // Concatenate item ids for each group
                DB::raw('Max(type) as type') // Concatenate item ids for each group
            )
            ->where('state', 1)->where('item',$id)
            ->where('isreported', 0)
            ->with(['branch_data', 'item']) // Get relationships
            ->groupBy(DB::raw('DATE(created_at)'),'type') // Group by date (ignoring time)
            ->orderBy('created_at', 'desc')
            ->paginate(200);

    return $this->SuccessResponse($Item, 200);
}
    
}

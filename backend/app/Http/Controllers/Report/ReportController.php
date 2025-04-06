<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\bank;
use App\Models\item;
use App\Models\Credit;
use App\Models\Casher;
use App\Models\MenuCategory;
use App\Models\TotalCash;


use App\Models\User;
use App\Models\itemLogs;
use App\Models\itemGroup;
use App\Models\category;
use App\Models\reporttr;
use App\Models\reportFolder;
use App\Models\branch;
use App\Models\report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Bank\BankRules\BankRules;
use App\Models\Expense;
use App\Models\Salary;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
class ReportController extends Controller
{
    public function GetBacnkReport($id=null){
        $query=[['state','=',1],['isreported','=','0']];
        if($id!=0){
            $query[]=['branch','=',$id];
        }
        $bank=bank::where($query)->with('branch')->orderBy('created_at', 'desc')->paginate(200);
        return $this->SuccessResponse($bank,200);
    }
    public function getReturnTransferReport($id=null){
        $query=[['state','=',1],['isreported','=','0']];
        if($id!=0){
            $query[]=['branch','=',$id];
        }
        $reporttr=reporttr::where($query)->with('branch')->with('item')->orderBy('created_at', 'desc')->paginate(200);
        return $this->SuccessResponse($reporttr,200);
    }
    public function getReturnTransferReportAll($id=null){
        $query=[['state','=',1],['isreported','=','0']];
        if($id!=0){
            $query[]=['branch','=',$id];
        }
        $reporttr=reporttr::where($query)->with('branch')->with('item')->orderBy('created_at', 'desc')->get();
        return $this->SuccessResponse($reporttr,200);
    }
    public function GetBacnkReportAll($id=null){
        $query=[['state','=',1],['isreported','=','0']];
        if($id!=0){
            $query[]=['branch','=',$id];
        }
        $bank=bank::where($query)->with('branch')->orderBy('created_at', 'desc')->get();
        return $this->SuccessResponse($bank,200);
    } 
    public function getReturnTransferReportAllReport(Request $request,$id=null){
        $x=1;
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
        $after=Carbon::parse(explode(' ',$date)[0].' 07:00');
        $now=Carbon::parse(explode(' ',$after)[0].' 07:00')->subDays($x);
    }else{
        $now=Carbon::parse($request->from);
        $after=Carbon::parse($request->to);
    }
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
        $branch=branch::where('user',Auth::user()->id)->first();
        $queryReport[]= ['branch','=',$branch->id];
    }
    $reporttr=reporttr::where($queryReport)->with('branch')->with('item')->orderBy('created_at', 'desc')->get();
    return $this->SuccessResponse($reporttr,200);

    }
    public function GetBacnkReportAllReport(Request $request,$id=null){
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
        $after=Carbon::parse(explode(' ',$date)[0].' 07:00');
        $now=Carbon::parse(explode(' ',$after)[0].' 07:00')->subDays($x);
    }else{
        $now=Carbon::parse($request->from);
        $after=Carbon::parse($request->to);
    }
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
        $branch=branch::where('user',Auth::user()->id)->first();
        $queryReport[]= ['branch','=',$branch->id];
    }

    $bank=bank::where($queryReport)->with('branch')->orderBy('created_at', 'desc')->get();
    return $this->SuccessResponse($bank,200);

    }
    public function GetDashboredReport(Request $request,$id){
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
        $after=Carbon::parse(explode(' ',$date)[0].' 07:00');
        $now=Carbon::parse(explode(' ',$after)[0].' 07:00')->subDays($x);
    }else{
        $now=Carbon::parse($request->from);
        $after=Carbon::parse($request->to);
    }
    $branch_data=branch::whereIn('state',[1,-1])->select('state as id',DB::raw('count(*) as total,state'))->groupBy('state')->get();
    // return $this->SuccessResponse($branch,200);
    $b_holder = [ 'total'=> 0, 'disabled'=> 0 ];
    for ($i = 0; $i < count($branch_data); $i++) {
        $b_holder['total'] += $branch_data[$i]->total;
        if ($branch_data[$i]->state == -1) {
            $b_holder['disabled'] = $branch_data[$i]->total;
        }
    }
    $query=[];
    $queryReport = [
        ['state', 1],
        ['isreported', '0'],
        ['created_at','>',$now],
        ['created_at','<',$after]
    ];
    if ($request->isreported) {
        $queryReport = [
            ['state', 1],
            ['created_at','>',$now],
            ['created_at','<',$after]
        ];
        if ($request->reported_date) {
            $queryReport[] =['isreported','=', $request->reported_date];
        }
    }
    // if ($request->ismain) {
    //     $branch=branch::where('user',Auth::user()->id)->first();
    //     $queryReport[]= ['branch',$branch->id];
    //     $query[]=['branch',$branch->id];
    // }

    $report_d=reporttr::where($queryReport)->select(DB::raw('SUM(amount*sell_price) as totalCash'),DB::raw('SUM(amount) as sold'))->first();
    
    $items=item::whereIn('state',[1,-1,4])->where('type',0)->where($query)->select('state',DB::raw('count(*) as total,state'), DB::raw('SUM(amount - sold) as inStock,state'),
    DB::raw('SUM(sold) as sold,state'),)->groupBy('state')->get();
    $itemGroup=itemGroup::whereIn('state',[1,-1])->select('state',DB::raw('count(*) as total,state'))->groupBy('state')->get();
    $Category=category::whereIn('state',[1,-1])->select('state',DB::raw('count(*) as total,state'))->groupBy('state')->get();
    $report = [ 'items'=> [], 'item'=> [], 'category'=>[], 'report'=> [], 'b_holder'=>$b_holder ];
    $total=['inStock'=>0,'sold'=>0];
    $disabled=['total'=>0];
    $outofStock=['total'=>0];
    $disabled_item=['total'=>0];
    $total_item=['total'=>0];
    $disabled_cate=['total'=>0];
    $total_cate=['total'=>0];
    for($i=0;$i<count($items);$i++){
        if($items[$i]->state==1){
            $total['inStock']=$items[$i]->inStock;
            $total['sold']=$items[$i]->sold;
        }elseif($items[$i]->state==-1){
            $disabled['total']=$items[$i]->total;
        }elseif($items[$i]->state==4){
            $outofStock['total']=$items[$i]->total;
        }
    }
    for($i=0;$i<count($itemGroup);$i++){
        if($itemGroup[$i]->state==1){
            $total_item['total']=$itemGroup[$i]->total;
        }elseif($itemGroup[$i]->state==-1){
            $disabled_item['total']=$itemGroup[$i]->total;
        }
    }
    for($i=0;$i<count($Category);$i++){
        if($Category[$i]->state==1){
            $total_cate['total']=$Category[$i]->total;
        }elseif($itemGroup[$i]->state==-1){
            $disabled_cate['total']=$Category[$i]->total;
        }
    }
    $inStore= $Item=item::where('state',1)->where('type',1)->count();
    $report['items_in_store']=$inStore;

    
    $report['report']['total'] = $report_d->sold ? $report_d->sold : 0;
    $report['report']['totalCash'] = $report_d->totalCash ? $report_d->totalCash : 0;
    $report['items']['total'] = $total;
    $report['items']['disabled'] = $disabled;
    $report['items']['outofStock'] = $outofStock;
    $report['item']['total'] = $total_item;
    $report['item']['disabled'] = $disabled_item;
    $report['category']['total'] = $total_cate;
    $report['category']['disabled'] = $disabled_cate;
    return $this->SuccessResponse($report,200);
    }
    public function GetReportDashboredAll(Request $request,$id){
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
        $after=Carbon::parse(explode(' ',$date)[0].' 07:00');
        $now=Carbon::parse(explode(' ',$after)[0].' 07:00')->subDays($x);
    }else{
        $now=Carbon::parse($request->from);
        $after=Carbon::parse($request->to);
    }
    $queryReport=[];
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
        $branch=branch::where('user',Auth::user()->id)->first();
        $queryReport[]= ['branch','=',$branch->id];
    }
    $report_d=report::where($queryReport)->with('branch_data')->with('itemgroups')->get();
    return $this->SuccessResponse($report_d,200);
    }
    public function GetReportByDate(Request $request){  
        $now=Carbon::parse($request->from);
        $after=Carbon::parse($request->to);
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
    // if ($request->ismain) {
    //     $branch=branch::where('user',Auth::user()->id)->first();
    //     $queryReport[]= ['branch','=',$branch->id];
    // }

    $report_d=reporttr::where($queryReport)->select(DB::raw('SUM(amount*sell_price) as totalCash'),DB::raw('SUM(amount) as sold'))->first();
     return $this->SuccessResponse($report_d,200);
    }
    public function Reported(){
        $reportFolder=(new reportFolder)->StorereportFolder();
        $Expense=Expense::where('isreported','0')->update(['isreported'=>$reportFolder->date]);
        $Salary=Salary::where('isreported','0')->update(['isreported'=>$reportFolder->date]);
        $report=report::where('isreported','0')->update(['isreported'=>$reportFolder->date]);
        $reporttr=reporttr::where('isreported','0')->update(['isreported'=>$reportFolder->date]);
        $casher=Casher::where('isreported','0')->update(['isreported'=>$reportFolder->date]);
        $itemLogs=itemLogs::where('isreported','0')->update(['isreported'=>$reportFolder->date]);
        $items=item::whereIn('state',[1,-1,4])->update(['amount'=>DB::raw('amount-sold'),'sold'=>0]);
    return $this->SuccessResponse('success',200);

    }
    public function ReportFolder(){
        $reportFolder=reportFolder::orderBy('created_at', 'desc')->paginate(200);
        return $this->SuccessResponse($reportFolder,200);
    }
    public function ReportFolderAll(){
        $reportFolder=reportFolder::orderBy('created_at', 'desc')->get();
        return $this->SuccessResponse($reportFolder,200);
    }
    public function GetReportByFilter($id){
        $report=report::where('item',$id)->where('isreported','0')->orderBy('created_at', 'desc')->get();
        return $this->SuccessResponse($report,200);
    }
    
    public function GetDashboredReportCasherBank(Request $request,$id){
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
        $after=Carbon::parse(explode(' ',$date)[0].' 07:00');
        $now=Carbon::parse(explode(' ',$after)[0].' 07:00')->subDays($x);
    }else{
        $now=Carbon::parse($request->from);
        $after=Carbon::parse($request->to);
    }
    $query=[];
    $queryReport = [
        ['isreported', '0'],
        ['created_at','>',$now],
        ['created_at','<',$after]
    ];
    if ($request->isreported) {
        $queryReport = [
            ['created_at','>',$now],
            ['created_at','<',$after]
        ];
        if ($request->reported_date) {
            $queryReport[] =['isreported','=', $request->reported_date];
        }
    }
   
    if(Auth::user()->role!='Admin'){
        $queryReport[] =['aut_id','=', Auth::user()->id];
    }
    $report_b=Casher::where($queryReport)->wherenotnull('bank')->select(DB::raw('SUM(bank_money) as price'),'bank')->with('bank')->groupBy('bank')->get();
   
    return $this->SuccessResponse($report_b,200);
    }
    public function GetDashboredReportCasher(Request $request,$id){
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
        $after=Carbon::parse(explode(' ',$date)[0].' 07:00','Africa/Nairobi')->setTimezone('UTC');
        $now=Carbon::parse(explode(' ',$after)[0].' 07:00','Africa/Nairobi')->setTimezone('UTC')->subDays($x);
    }else{
        $now = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $after = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $now=Carbon::parse($now,'Africa/Nairobi')->setTimezone('UTC');
        $after=Carbon::parse($after,'Africa/Nairobi')->setTimezone('UTC');
    }
    $query=[];
    $queryReport = [
        ['isreported', '0'],
        ['created_at','>',$now],
        ['created_at','<',$after]
    ];
    if ($request->isreported) {
        $queryReport = [
            ['created_at','>',$now],
            ['created_at','<',$after]
        ];
        if ($request->reported_date) {
            $queryReport=[['isreported','=', $request->reported_date]];
        }
    }
    $casher_report_holder=[];
    if(Auth::user()->role=='Casher'){
        $casher_report_holder=['aut_id'=>Auth::user()->id,'given'=>0];
        $queryReport=[];
    }
    // return [$after,$now];
    // return $queryReport;
    $TotalCash=TotalCash::where($queryReport)->sum('money');
    $cashers = Casher::where($casher_report_holder)->where($queryReport)
    ->where('state', 0)
    ->get();

// Step 3: Process Data in PHP (Grouping & Summing)
$non_uuid_orders = $cashers->filter(function ($casher) {
    return !preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/', $casher->orders);
});
$total_price = $non_uuid_orders->sum('order_price');
$menu1=[];
foreach($non_uuid_orders as $a){
    $total_price+=$a->order_price;
    $menu1[]=['name'=>$a->orders,'casher'=>[$a],'price'=>$a->order_price,'cost'=>0];
}


$menu_report = MenuCategory::whereHas('menu', function ($query) use ($cashers) {
    $query->whereIn('id', $cashers->pluck('orders'));
})->with('menu')->get();
foreach($menu_report as $rep){
    foreach($rep->menu as $menu){
        $menu['casher']=$cashers->where('orders', $menu->id)->values()->toArray();
    }
}


$menu_report[] = ['name' => 'Other', 'menu' =>$menu1];

// Step 4: Calculate Other Reports in PHP
$report_e = Expense::whereBetween('created_at', [$now, $after])->sum('amount');
$report_s = Salary::whereBetween('created_at', [$now, $after])->sum('money');
$report_c = Credit::whereBetween('created_at', [$now, $after])->where('state', 0)->sum('money');
$report_u = User::where('state', 1)->sum('credit');

$report_g = $cashers->where('is_gift', 1)->sum('price');
$report_credit = $cashers->where('credit', '!=', 0)->sum('price');
$report_b['price'] = $cashers->sum('bank_money');

$report_d = $cashers->where('isdelivery', 0)
    ->groupBy('isflaged')
    ->map(function ($group) {
        return [
            'orders' => $group->count(),
            'price' => $group->sum('price'),
            'flag' => $group->max('isflaged')
        ];
    });

$report_dd = $cashers->where('isdelivery', 1)
    ->groupBy('isflaged')
    ->map(function ($group) {
        return [
            'orders' => $group->count(),
            'price' => $group->sum('price') - $group->sum('fee'),
            'fee' => $group->sum('fee'),
            'flag' => $group->max('isflaged')
        ];
    });
    foreach($report_dd as $rep){
        $rep->price-=$rep->fee;
    }
    return $this->SuccessResponse([$report_d,$report_dd,$report_e,$report_s,$report_c,$report_u,$report_g,$report_b,$TotalCash,$menu_report,$report_credit],200);
    }
   
    
    public function ExportExpensese(Request $request,$id){
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
        $after=Carbon::parse(explode(' ',$date)[0].' 07:00','Africa/Nairobi')->setTimezone('UTC');
        $now=Carbon::parse(explode(' ',$after)[0].' 07:00','Africa/Nairobi')->setTimezone('UTC')->subDays($x);
    }else{
        $now=Carbon::parse($request->from);
        $after=Carbon::parse($request->to);
    }
    $query=[];
    $queryReport = [
        ['isreported', '0'],
        ['created_at','>',$now],
        ['created_at','<',$after]
    ];
    if ($request->isreported) {
        $queryReport = [
            ['created_at','>',$now],
            ['created_at','<',$after]
        ];
        if ($request->reported_date) {
            $queryReport=[['isreported','=', $request->reported_date]];
        }
    }
    // return [$after,$now];
    // return $queryReport;
  
    
    $report_e=Expense::where($queryReport)->with('expense')->get();
  
    
    return $this->SuccessResponse($report_e,200);
    }
    public function GetReportByFilterCasher(Request $request){  
        $now=Carbon::parse($request->from);
        $after=Carbon::parse($request->to);
    $queryReport = [
        ['isreported','=', '0'],
        ['created_at','>',$now],
        ['created_at','<',$after]
    ];
    if ($request->isreported) {
        $queryReport = [
            ['created_at','>',$now],
            ['created_at','<',$after]
        ];
        if ($request->reported_date) {
            $queryReport=[['isreported','=', $request->reported_date]];
        }
    }
   // return [$after,$now];
    // return $queryReport;
    $TotalCash=TotalCash::where($queryReport)->sum('money');
    $cashers = Casher::where($queryReport)
    ->where('state', 0)
    ->get();

// Step 3: Process Data in PHP (Grouping & Summing)
$non_uuid_orders = $cashers->filter(function ($casher) {
    return !preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/', $casher->orders);
});
$total_price = $non_uuid_orders->sum('order_price');
$menu1=[];
foreach($non_uuid_orders as $a){
    $total_price+=$a->order_price;
    $menu1[]=['name'=>$a->orders,'casher'=>[$a],'price'=>$a->order_price,'cost'=>0];
}


$menu_report = MenuCategory::whereHas('menu', function ($query) use ($cashers) {
    $query->whereIn('id', $cashers->pluck('orders'));
})->with('menu')->get();
foreach($menu_report as $rep){
    foreach($rep->menu as $menu){
        $menu['casher']=$cashers->where('orders', $menu->id)->values()->toArray();
    }
}


$menu_report[] = ['name' => 'Other', 'menu' =>$menu1];

// Step 4: Calculate Other Reports in PHP
$report_e = Expense::whereBetween('created_at', [$now, $after])->sum('amount');
$report_s = Salary::whereBetween('created_at', [$now, $after])->sum('money');
$report_c = Credit::whereBetween('created_at', [$now, $after])->where('state', 0)->sum('money');
$report_u = User::where('state', 1)->sum('credit');

$report_g = $cashers->where('is_gift', 1)->sum('price');
$report_credit = $cashers->where('credit', '!=', 0)->sum('price');
$report_b['price'] = $cashers->sum('bank_money');

$report_d = $cashers->where('isdelivery', 0)
    ->groupBy('isflaged')
    ->map(function ($group) {
        return [
            'orders' => $group->count(),
            'price' => $group->sum('price'),
            'flag' => $group->max('isflaged')
        ];
    });

$report_dd = $cashers->where('isdelivery', 1)
    ->groupBy('isflaged')
    ->map(function ($group) {
        return [
            'orders' => $group->count(),
            'price' => $group->sum('price') - $group->sum('fee'),
            'fee' => $group->sum('fee'),
            'flag' => $group->max('isflaged')
        ];
    });
    foreach($report_dd as $rep){
        $rep->price-=$rep->fee;
    }
    return $this->SuccessResponse([$report_d,$report_dd,$report_e,$report_s,$report_c,$report_u,$report_g,$report_b,$TotalCash,$menu_report,$report_credit],200);
    }
    public function getGraphdata(){
     // Casher report grouped by day
$report_g = Casher::where('state', 0)->where('isflaged', 0)
->where('isreported', 0)
->select(
    DB::raw('DATE(created_at) as day'),
    DB::raw('SUM(CASE WHEN is_gift = 0 THEN price ELSE 0 END) as price'), // Sum price only if not a gift
    DB::raw('SUM(CASE WHEN is_gift = 0 THEN fee ELSE 0 END) as fee')      // Sum fee only if not a gift
)->limit(1000)
->groupBy('day')
->get();

// User report grouped by day
$report_u = Casher::where('state', 0)->where('credit','!=','0')
->select(
    DB::raw('DATE(created_at) as day'),
    DB::raw('SUM(price) as total_credit')
)->limit(1000)
->groupBy('day')
->get();
// Expense report grouped by day
$report_e = Expense::where('isreported', 0)
->select(
    DB::raw('DATE(created_at) as day'),
    DB::raw('SUM(amount) as total_expense')
)->limit(1000)
->groupBy('day')
->get();

// Salary report grouped by day
$report_s = Salary::where('isreported', 0)
->select(
    DB::raw('DATE(created_at) as day'),
    DB::raw('SUM(money) as total_salary')
)->limit(1000)
->groupBy('day')
->get();

// Credit report grouped by day
$report_c = Credit::where('isreported', 0)
->where('state', 0)
->select(
    DB::raw('DATE(created_at) as day'),
    DB::raw('SUM(money) as total_credit_money')
)->limit(1000)
->groupBy('day')
->get();
$dailyReport = [];

// Loop through each report and merge data by day
foreach ($report_g as $g) {
    $dailyReport[$g->day] = [
        'day' => $g->day,
        'price' => $g->price,
        'fee' => $g->fee,
    ];
}

foreach ($report_u as $u) {
    if (!isset($dailyReport[$u->day])) {
        $dailyReport[$u->day] = ['day' => $u->day];
    }
    $dailyReport[$u->day]['total_credit'] = $u->total_credit;
}

foreach ($report_e as $e) {
    if (!isset($dailyReport[$e->day])) {
        $dailyReport[$e->day] = ['day' => $e->day];
    }
    $dailyReport[$e->day]['total_expense'] = $e->total_expense;
}

foreach ($report_s as $s) {
    if (!isset($dailyReport[$s->day])) {
        $dailyReport[$s->day] = ['day' => $s->day];
    }
    $dailyReport[$s->day]['total_salary'] = $s->total_salary;
}

foreach ($report_c as $c) {
    if (!isset($dailyReport[$c->day])) {
        $dailyReport[$c->day] = ['day' => $c->day];
    }
    $dailyReport[$c->day]['total_credit_money'] = $c->total_credit_money;
}

// Convert the associative array to an indexed array of objects
$finalReport = array_values($dailyReport);

// Sort the array by the 'day' field in ascending order (oldest to newest)
usort($finalReport, function ($a, $b) {
    // Sorting by day (YYYY-MM-DD format) ensures chronological order
    return strtotime($a['day']) - strtotime($b['day']);
});

// Return the sorted result as a JSON response
return response()->json($finalReport);

    }
}

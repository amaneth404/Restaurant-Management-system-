<?php

namespace App\Http\Controllers\User;

use Exception;
use App\Models\User;
use App\Models\branch;
use App\Models\Salary;
use App\Models\Credit;
use App\Models\UsersStatus;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use App\Services\AuthService;
use App\Http\Controllers\Controller;
use App\Models\UserLoginHistory;
use App\Http\Controllers\User\UserRule;
use Illuminate\Encryption\Encrypter; 
class UserController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('sanitize')->only(['register']);
    // }og in a user
    public function GetCredit(){
        return Credit::where('money','>',0)->where('state',0)->with('user')->paginate(50);
    }
    public function PayCredit(Request $request){
        $credit=Credit::findorfail($request->id);
        $credit->money-=$request->money;
        $credit->paid+=$request->money;
        $credit->casher=Auth::user()->id;
        $credit->save();
        $credit1=new Credit;
        $credit1->id=Str::uuid();
        $credit1->user_id=$credit->user_id;
        $credit1->money=$credit->money;
        $credit1->paid=$credit->paid;
        $credit1->phone_number=$credit->phone_number;
        $credit1->full_name=$credit->full_name;
        $credit1->paid=$credit->paid;
        $credit1->casher=Auth::user()->id;
        $credit1->state=1;
        $credit1->save();
    }
    public function UserProfile(){
        $user=User::where('id',Auth::user()->id)->with('branch')->first();
        return $this->SuccessResponse($user,200);
    }
    public function UserProfileWaitre(){
        $user=User::where('state',1)->wherein('role',['Waitre','Waiter'])->get();
        return $this->SuccessResponse($user,200);
    }
    public function UserProfilechef(){
        $user=User::where('state',1)->wherein('role',['Chef','Chef'])->get();
        return $this->SuccessResponse($user,200);
    }
    public function updateUserProfile(Request $request){
        $user=User::findOrfail(Auth::user()->id);
        $branch=branch::where('user',$user->id)->first();
     $this->validate($request,UserRule::UpdateUsersRule);
     $user=(new User)->UpdateUser($request,$user,User::UpdateUserProfile);
     return $this->SuccessResponse($user,200); 
    }
    public function GetUserProfile($id){
        $user=User::where('id',$id)->with('branch')->first();
        return $this->SuccessResponse($user,200);
    }
    public function changephonenumber(Request $request){
        $user=User::findOrfail(Auth::user()->id);
        $this->validate($request,UserRule::ChangePhoneNumber($user->id));
        if(Hash::check($request->password, $user->password)){
        $user->phone_number=$request->phone_number;
        $user->save();
        return $this->SuccessResponse($user,200);
        }
        return $this->ErrorResponse(['password'=>['wrong password !']],422);

    }
    public function changeUsername(Request $request){
        $user=User::findOrfail(Auth::user()->id);
        $this->validate($request,UserRule::changeUsername($user->id));
        if(Hash::check($request->password, $user->password)){
        $user->username=$request->username;
        $user->save();
        return $this->SuccessResponse($user,200);
        }
        return $this->ErrorResponse(['password'=>['wrong password !']],422);

    }
    public function changeUserPassword(Request $request){
        $user=User::findOrfail(Auth::user()->id);
        $this->validate($request,UserRule::changePassword);
        if(Hash::check($request->old_password, $user->password)){
        $user->password= Hash::make($request->password);
        $user->save();
        return $this->SuccessResponse($user,200);
        }
        return $this->ErrorResponse(['password'=>['wrong password !']],422);

    }
    public function index(){
        $user=User::where('role','!=','Admin')->where('state','!=',-2)->with('branch')->paginate(200);
       return $this->SuccessResponse($user,200);
    }
    public function SearchUsers(Request $request){
        $user=User::where('role','!=','Admin')->where('username','like','%'.$request->username.'%')->with('branch')->get();
       return $this->SuccessResponse($user,200);
    }
    public function showUser($id){
        $user=User::where('id',$id)->where('role','manager')->with('branch')->first();
       return $this->SuccessResponse($user,200);
    }
   public function Pay($id){
    $user=User::findorfail($id);
    $left=$user->salary-$user->credit;
    if($left<0){
        $user->credit=$left*-1;
    }else{
        $user->credit=0;
    }
    $user->save();
    (new Salary)->StoreSalary($user->id,$user->salary);
   }
   public function Remark(Request $request,$id){
    $user=User::findorfail($id);
    if($user->remark){
        $holder=json_decode($user->remark);
        $holder[]=['remark'=>$request->remark,'date'=>Carbon::now(),'menu'=>$request->menu,'time'=>$request->time];
        $user->remark=json_encode($holder);
    }else{
        $user->remark=json_encode([['remark'=>$request->remark,'date'=>Carbon::now(),'menu'=>$request->menu,'time'=>$request->time]]);
    }
    $user->save();
   }
   public function GetSalary(){
    return Salary::with('user')->orderBy('created_at','desc')->paginate(50);
   }
   public function Store(Request $request){
        $this->validate($request,UserRule::RegisterEmployeRule);
        $user=(new User)->StoreEmploye($request,$request->role);
        $holder=['title'=>$request->title,'location'=>$request->location,'user'=>$user->id];
         $branch=(new branch)->Storebranch($holder);
        $user['branch']=$branch;
        return $this->SuccessResponse($user,200); 
   }
   public function ChangePassword(Request $request,$id){
       $user=User::findorfail($id);
    $this->validate($request,UserRule::ChangeEmployePasswordRule);
    $user->password=Hash::make($request->password);
    $user->save();
    return $this->SuccessResponse('success',200); 

   }
   public function Update(Request $request,$id){
       $user=User::findOrfail($id);
       $branch=branch::where('user',$user->id)->first();
    $this->validate($request,UserRule::RegisterUpdateEmployeRule($id));
    $usr=(new User)->UpdateUser($request,$user,User::UpdateEmployeProfile);
    $holder=['title'=>$request->title,'location'=>$request->location,'user'=>$user->id];
     $branch=(new branch)->Updatebranch($request,$branch,branch::Updatebranch);
    $user['branch']=$branch;
    return $this->SuccessResponse($user,200); 
   }
   public function GetBranches(){
       $branch=branch::where('state',1)->get();
       return $this->SuccessResponse($branch,200);
   }
   public function GetBranchesWithAdmin(){
    $branch=branch::where('state',1)->where('role','!=','Admin')->get();
    return $this->SuccessResponse($branch,200);
} 
public function Delete($id){
    $user=User::findorfail($id);
    $branch=branch::where('user',$user->id)->first();
    $branch->state=-1;
    $branch->title=$branch->title.' Deleted Branch';
    $branch->save();
    $user->username=$user->username.' Deleted Acount';
    $user->phone_number=$user->phone_number.' Deleted Acount';
    $user->state=-2;
    $user->save();
}
public function DActivet($id){
    $user=User::findorfail($id);
    $branch=branch::where('user',$user->id)->first();
    $user->state=-1;
    $branch->state=-1;
    $branch->save();
    $user->save();
}
public function Activet($id){
    $user=User::findorfail($id);
    $branch=branch::where('user',$user->id)->first();
    $user->state=1;
    $branch->state=1;
    $branch->save();
    $user->save();
}
}
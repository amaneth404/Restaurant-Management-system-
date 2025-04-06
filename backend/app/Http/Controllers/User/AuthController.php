<?php

namespace App\Http\Controllers\User;

use Exception;
use App\Models\User;
use App\Models\branch;
use App\Models\UsersStatus;
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
class AuthController extends Controller
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
    public function login(Request $request)
    {   
        $this->validate($request,UserRule::LoginRule);
       
        $user = (new User)->AuthenticateUser($request);
        if (!$user||$user->state!=1||$user->role=='Waitre'){
            return $this->TestAutorization();
        }
        try {
            if (!(new AuthService)->isLoginPasswordCorrect($request,$user)){
                return $this->TestAutorization();
            }
            $token=Auth::login($user);
            return $this->respondWithToken($token,Auth::user()->role,$user);
        } catch (Exception $error) {
            return $this->ErrorResponse([
                'message' => 'Error occured while loggin in.',
                'error' => $error,
            ],500);
        }
    }
    protected function respondWithToken($token,$role,$user){
        return $this->SuccessResponse([
            'token'=>$token,
            'role'=>$role, 
            'user'=>$user,
            'token_type'=>'bearer',
            'expires_in'=>Auth::factory()->getTTL()*60
        ],200);
    }
    public function throttleKey()
    {
        return Str::lower(request('email')) . '|' . request()->ip();
    }
    protected function TestAutorization(){
        return $this->ErrorResponse(['username'=>['wrong username or password !']],422);
    }
    public function checkTooManyFailedAttempts()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 10)) {
            return;
        }

        throw new Exception('IP address banned. Too many login attempts.');
    }
    public function RegisterUser(Request $request){
        $key='$2y$10$Qb6qF2iFvkIlEAVcj4y9Eem8FA0MF7WjVdQT3N.W3nRHAgnPVk1FG';
        $user=User::All();
        $root=$request->root();
        if(Hash::check($root, '$2y$10$dtwy/a3Z66c8Ab6m4tSkkeLxVX7/QaPkb9FJE3G4/COABHQdr8Qly')){
        if(count($user)==0){
        $this->validate($request,UserRule::RegisterRule);
        // if(!Hash::check($request->key, $key)){
        //     return $this->ErrorResponse('invalid request !!',404);
        // }
        $title=$request->title;
        $branch=$request->location;
        $password=$request->password;
        $request=$request->except(['key','title','location','password_confirmation','password']);
        $request['password']=Hash::make($password);
        $request['role']='Admin';
        $user=(new User)->StoreUser($request);
        $holder=['title'=>$title,'location'=>$branch,'user'=>$user->id];
        (new branch)->Storebranch($holder);
        return $this->SuccessResponse('success',200);
    }else{
        return $this->ErrorResponse('invalid request !!',404);
    }
}else{
    return $this->ErrorResponse('invalid request !!',405);
}
    }
    public function logout(){
        Auth::logout();
        return response()->json(['message'=>'Successfully logged out!']);
    }
}
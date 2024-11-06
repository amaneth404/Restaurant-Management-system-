<?php

namespace App\Models;
use App\Services\AuthService;
use App\Services\UserService;
use App\Models\ResetRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Auth\Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
class User extends Model implements AuthorizableContract,AuthenticatableContract,JWTSubject
{
    use Authenticatable, Authorizable, Softdeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    // Role Constants
    const USER = 0;
    //update Values
    const UpdateProfile=['first_name','last_name','middle_name'];
    const UpdateEmployeProfile=['first_name','last_name','middle_name','username','phone_number','role','salary'];
    const UpdateUserProfile=['first_name','last_name','middle_name'];
    const UpdateUser=['first_name','last_name','middle_name'];
    //end of update values
    protected $fillable = [
        'id',
        "username",
        "first_name",
        "last_name",
        "middle_name",
        "phone_number",
        "state",
        "salary",
        'role',
        "password",
        'branch'
    ];

    protected $hidden = [
        'password',
        'deleted_at',
        'username_change_at'
    ];
    public function branch(){
        return $this->hasone('App\Models\branch','user');
    }
    public function StoreEmploye($request,$role){
        $user=new User;
        $request->merge([
            'id'=>Str::uuid(),
            'state'=>1,
            'role'=>$role,
            'password'=>Hash::make($request->password)
        ]);  
        return $user->create($request->all());
    }
    public function StoreUser($request){
        $user=new User;
        $request['id'] = (string) Str::uuid();        
        return $user->create($request);
    }
    public function UpdateUser($request,$user,$update_values,$file_name=''){
         foreach($update_values as $value){
            if(filled($request->$value)&&$request->$value!='null'){
            $user->$value=$request->$value;
            }
        }
        if($file_name){
            $user->image=URL::to('storage/uploads/images/'.$file_name);
        }
        $user->save();
        return $user;
    }
    // public static function changePhoneNumberOrEmail($request,$user,$type){
    //     $response=(new UserService)->isResetCodeCorrect($request,$user,$type);
    //     if($response[0]==ResetRequest::Active){
    //         $input_type=ResetRequest::ResetEmail==$type?'email':'phone_number';
    //         $user->$input_type=$response[1]->value;
    //         $user->save();
    //         $response[1]->delete();
    //     }
    //         return $response[0];
       
    // }
    // public static function ConfirmUser($request,$user,$type){
    //     $response=(new UserService)->isConfirmationCodeCorrect($request,$user,$type);
    //     if($response[0]==ResetRequest::Active){
    //         $input_type=ResetRequest::ResetEmail==$type?'email':'phone_number';
    //         $user->$input_type=$response[1]->value;
    //         $user->save();
    //         $response[1]->delete();
    //         return ['response'=>[$response[2]],'status'=>[true]];
    //     }
    //     return ['response'=>[$response[1]],'status'=>[false]];
       
    // }
    public static function changeUserName($request,$user){
        $user->username=$request->username;
        $user->username_change_at=\Carbon\Carbon::now();
        $user->save();
        return $user;
    }
    public static function changePassword($request, $user)
    {

        if (!(new AuthService)->isCurrentPasswordCorrect($request))
            return false;

        $user->password = app('hash')->make($request->password);
        $user->save();

        return true;
    }
    public function AddToTrashUser($user){
        $user->delete();
    }
    public function PermanentDelete($user){
        $user->forceDelete();
    }
    public function DisplayTrash($id){
        return User::findOrfail($id)->trashed();
    }
    public function RecoverTrash($id){
        return User::findOrfail($id)->recover();
    }
    public function AuthenticateUser($request){
     return User::where('email', $request->username)
        ->orWhere('phone_number', $request->username)
        ->orWhere('username', $request->username)
        ->first();
    }

    //Activate or deactivate users
    public static function activateUser($id)
    {
        return User::findOrFail($id)->restore();
    }

    public static function deactivateUser($id)
    {
        return User::findOrFail($id)->delete();
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}

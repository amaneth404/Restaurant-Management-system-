<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ResetRequest;
use App\Traits\RequestResponse;
class UserService
{
    use RequestResponse;
   public static function UsernameUpdatedController($user,$date){
       if($user->username_change_at){
            if($date->diffInDays($user->username_change_at)<=60){
                return false;
            }
         }
       return true;
   }
   public function isResetCodeCorrect($request,$user,$type){
        $reset_request=ResetRequest::latest()->where('user_id',$user->id)->where('type',$type)->where('reset_code',$request->code)->first();
       if($reset_request){
            if(Carbon::now()->diffInMinutes($reset_request->created_at)<=5){
                return [ResetRequest::Active,$reset_request];
            }else{
                return [ResetRequest::ExpiredRequest];
            }
        }else{
            return [ResetRequest::NotFoundRequest];
        }
   }
   public function isConfirmationCodeCorrect($request,$user,$type){
    $reset_request=ResetRequest::latest()->where('user_id',$user->id)->where('type',$type)->where('reset_code',$request->code)->first();
   if($reset_request){
        if(Carbon::now()->diffInDays($reset_request->created_at)<=7){
            return [ResetRequest::Active,$reset_request,['message'=>['Your Account is Confirmed']]];
        }else{
            return [ResetRequest::ExpiredRequest,['code'=>['Confirmation Code is Expired please try again']]];
        }
    }else{
        return [ResetRequest::NotFoundRequest,['code'=>'Confirmation Code is not found please try again']];
    }
}
   public function ResetCodeResponse($status,$type,$name){
       if(ResetRequest::Active==$status){
        return $this->SuccessResponse(
            ['code'=>[$name.' Your '.$type.' is Changed Successfully']],200
        );
        }else if(ResetRequest::ExpiredRequest==$status){
            return $this->ErrorResponse(
                ['code'=>['Reset Code is Expired please try again']],404
            );
        }else{
            return $this->ErrorResponse(
                ['code'=>['Reset Code is not found please try again']],404
            );
        }
    }
}

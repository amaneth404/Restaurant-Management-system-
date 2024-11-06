<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{
    public function respondWithToken($token,$role,$user){
        return response()->json([
            'access_token'=>$token,
            'role'=>$role, 
            'user'=>$user,
            'token_type'=>'bearer',
            'expires_in'=>Auth::factory()->getTTL()*60
        ]);
    }
    public function verifyPasswordResetCode($request, $model)
    {
        $user = $model::whereEmail($request->email)
            ->where('reset_code', $request->reset_code)
            ->firstOrFail();

        $timeLenInMins = Carbon::parse($user->reset_requested_at)
            ->diffInMinutes(Carbon::now());

        if ($timeLenInMins > 120)
            return null;

        return $user;
    }

    public function isCurrentPasswordCorrect($request)
    {
        return (Hash::check(
            $request->old_password,
            User::findOrFail(
                   Auth::user()->id
                )->password
        ));
    }
    public static function isLoginPasswordCorrect($request, $user)
    {
        return (Hash::check($request->password, $user->password));
    }
}

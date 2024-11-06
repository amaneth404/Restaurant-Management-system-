<?php
namespace App\Traits;

trait RequestResponse{
    public function SuccessResponse($data,$code){
        return response()->json($data,$code);
    }
    public function ErrorResponse($data,$code){
        return response()->json($data,$code);
    }
}
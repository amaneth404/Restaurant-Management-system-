<?php

namespace App\Services;

use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\RequestResponse;
use Illuminate\Support\Arr;
class FileService
{
    use RequestResponse;
    const IMAGE=['jpg','png'];
    const VIDEO=['mp4','mkv'];
    Public static function ImageProccessore($attachemt,$suported_extension,$type,$oldefile=''){
        $extension=$attachemt->extension();
        if($type=='video'){
            $filePath ='storage/uploads/videos/';
        }else if($type=='image'){
            $filePath ='storage/uploads/images/';
        }
        $fileName = date_format(date_create(), 'YmdHisu') . mt_rand(1000, 9999).'.'.$extension;
        $attachemt->move($filePath, $fileName);
        if($oldefile){
            $oldefile=$filePath.explode('/',$oldefile)[count(explode('/',$oldefile))-1];
            unlink($oldefile);
        }
        return ['status'=>'success','filename'=>$fileName];
    }
    public static function ExtensionTester($attachemt,$suported_extension){
        $extension=$attachemt->extension();
        foreach($suported_extension as $suported){
            if($suported==$extension){
                return true;
            }
        }
        return false;
    }
}

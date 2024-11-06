<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class Language extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable=[
        'title',
        'code',
        'id',
        'deleted_at'
    ];
    
    const UpdateLanguage=['title','code'];
    public function Store($request){

        $request->merge(['id'=>Str::uuid()]);
        $language=new Language;
        return $language->create($request->all());
    }
    public function UpdateLanguage($request,$language,$update_values){
        foreach($update_values as $value){
            $language->$value=$request->$value;
        }
        $language->save();
        return $language;
    }
}

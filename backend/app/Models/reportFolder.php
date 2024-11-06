<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class reportFolder extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable=[
        'date',
        'id',
        'deleted_at'
    ];
    const Updatereport=['date'];
    public function StorereportFolder(){
        $report=new reportFolder;
        return $report->create(['id'=>Str::uuid(),'date'=>Str::uuid()]);
    }
    public function UpdatereportFolder($request,$report,$update_values){
        foreach($update_values as $value){
            $report->$value=$request->$value;
        }
        $report->save();
        return $report;
    }
}

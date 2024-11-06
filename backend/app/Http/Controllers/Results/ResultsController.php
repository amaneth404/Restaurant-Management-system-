<?php

namespace App\Http\Controllers\Results;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Results;
use App\Models\Leagues;
use App\Models\Games;
use App\Http\Controllers\Results\ResultsRules\ResultsRules;
use App\Services\FileService;
use Illuminate\Support\Facades\URL;
class ResultsController extends Controller
{
    public function index(){
        $leaguse=Results::get();
        $holder=[];
        foreach($leaguse as $leag){
            $game=Games::where('id',$leag->game_id)->onlyTrashed()->first();
            if($game){
            $leag['games']=$game;
            $holder[]=$leag;
            }
        }
        return $this->SuccessResponse($holder,200);
    }
    public function FilterByLeag($id){
        $reuslts=Results::where('leag_id',$id)->get();
        return $this->SuccessResponse($reuslts,200);
    }
    public function SearchResult(Request $request){
            if(filled($request->search)){
                $games_id=Games::where('title','like',$request->search.'%')->where('isresult',true)->onlyTrashed()->pluck('id');
                $results=Results::whereIn('game_id',$games_id)->get();
                foreach($results as $leag){
                    $leag['games']=Games::where('id',$leag->game_id)->onlyTrashed()->first();
                }
                return $this->SuccessResponse($results,200);
            }
            return $this->SuccessResponse([],200);
    }
    public function GetResultsData(){
        $leaguse=Results::select('game_id','id','first_half','second_half','full_time')->get();
        $leag_id=[];
        
        $holder=[];
        foreach($leaguse as $leag){
            $data=Games::where('id',$leag->game_id)->onlyTrashed()->first();
            if($data){
            $leag['title']=trim($data->title);
            if(array_search($data->leag_id,$leag_id)===False){
            $holder[]=['title'=>Leagues::where('id',$data->leag_id)->withTrashed()->first()->title,'result'=>[$leag]];
            $leag_id[]=$data->leag_id;
            }else{
                $holder[array_search($data->leag_id,$leag_id)]['result'][]=$leag;
            }
           
        }
        }
        return $this->SuccessResponse($holder,200);
    }
    public function store(Request $request){
        $this->validate($request,ResultsRules::ResultsStoreRule);
        $result=(new Results)->StoreResults($request);
        $game=Games::where('id',$result->game_id)->onlyTrashed()->first();
        $game->isresult=true;
        $game->save();
        $result['games']=$game;
        return $this->successResponse($result,200);
    }
    public function update(Request $request,$id){
        $results=Results::findOrfail($id);
        $this->validate($request,ResultsRules::ResultsUpdateRule($results->id));
        $result=(new Results)->UpdateResults($request,$results,Results::UpdateResults);
        return $this->successResponse($result,200);
    }
    public function destroy($id){
        $results=Results::findOrfail($id);
        $game=Games::where('id',$results->game_id)->onlyTrashed()->first();
        if($game){
        $game->isresult=false;
        $game->save();
        }
        $results->delete();
        return $this->SuccessResponse(['message'=>['Successfully deleted']],200);
    }
}

<?php

namespace App\Http\Controllers;
use App\ActivityDpr;
use App\ActivityDprTomorrow;
use App\ExpenceDpr;
use App\ManpowerAttendance;
use App\VehicleDpr;
use App\State;
use App\Site;
use App\ActivityCategory;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Traits\SendMail;
use Illuminate\Support\Facades\View;
use PDF;
use App\TicketPause;
use DB;

class Controller extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, SendMail;
    
    public function __construct(){        
        // view()->share('ntData', 55);

        // $menu = array(
        //     'items' => array(),
        //     'parents' => array()
        // );
        // $obj = ActivityCategory::where('id','<>', '')->get();

        // foreach($obj as $k=>$items){
        //     $menu['items'][$items->id] = $items;
        //     $menu['parents'][$items->parent_id][] = $items->id;
        // }
        // View::share('activityCategory', $menu);
    }
    
    public $ajaxResponse = ["success"=>false,"msg"=>"","data"=>[]];

    public $chainage_gap = 100;
    public $paging = 10;

    public function getChainage($cfrom,$cto){        
        $result = [];
        $counter = 0;
        
        while($cto > $cfrom){
            $result[$cfrom] = $cfrom + $this->chainage_gap;            
            $cfrom = ($cfrom + $this->chainage_gap); 
            $counter++;
        }

        return $result;
    }

    public function checkAccess($user_id,$site_id){
        $result = DB::table('site_access')->where('site_id',$site_id)->where('user_id',$user_id)->first();
        
        if(isset($result->id)){
            return true;
        }
        return false;
    }

    public function getStatesByCountry(Request $request){
        try{
            
            $result = State::getStateByCountry($request->id);                                    
            return response()->json(['status'=>1,'message'=>'','data'=>$result]);    
        }catch(Exception $e){			
            return response()->json(['status'=>0,'message'=>'Error','data'=>json_decode("{}")]);    
        }
    }
}

<?php
//amary@321! amary
namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Log;
use App\Poletype;
use App\Division;
use App\SiteData;
use App\Location;
use App\Feeder;
use App\Siteinfo;
use Config;

class CommonController extends Controller
{
    /**
   * ge Home pate method
   * @return success or error
   * 
   * */
  public function getPoleTypes(Request $request){
    
    try{
      $status = 0;
      $message = "";    
      
      
      $result = Poletype::orderBy('name','asc')->get();
      if($result->count() > 0){
          return response()->json(['status'=>1,'message'=>'','data'=>$result]);                    
      }else{
          return response()->json(['status'=>$status,'message'=>'No Pole type Found','data'=>json_decode("{}")]);                    
      }   
    }catch(Exception $e){
      return response()->json(['status'=>$status,'message'=>'Exception Error','data'=>json_decode("{}")]);                    
    }
            
  }

  public function getDivisions(Request $request){
    
    try{
      $status = 0;
      $message = "";    
      
      
      $result = Division::orderBy('name','asc')->get();
      if($result->count() > 0){
          return response()->json(['status'=>1,'message'=>'','data'=>$result]);                    
      }else{
          return response()->json(['status'=>$status,'message'=>'No Pole type Found','data'=>json_decode("{}")]);                    
      }   
    }catch(Exception $e){
      return response()->json(['status'=>$status,'message'=>'Exception Error','data'=>json_decode("{}")]);                    
    }
            
  }

  public function getDivisionBySiteId(Request $request){
    
    try{
      $status = 0;
      $message = "";    
      
      $validator = Validator::make($request->all(), [
          'site_id' => 'required',                                                    
      ]);           
      if($validator->fails()){
        $error = json_decode(json_encode($validator->errors()));
        if(isset($error->site_id)){
          $message = $error->site_id[0];
        }
        return response()->json(["status"=>$status,"message"=>$message,"data"=>json_decode("{}")]);
      } 

      $site_id = $request->site_id;

      $result = SiteData::getDivisionBySite($site_id);
      if($result->count() > 0){
          return response()->json(['status'=>1,'message'=>'','data'=>$result]);                    
      }else{
          return response()->json(['status'=>$status,'message'=>'No data Found','data'=>json_decode("{}")]);                    
      }   
    }catch(Exception $e){
      return response()->json(['status'=>$status,'message'=>'Exception Error','data'=>json_decode("{}")]);                    
    }
            
  }

  public function getLocations(Request $request){
    
    try{
      $status = 0;
      $message = "";    
      
      
      $result = Location::orderBy('name','asc')->get();
      if($result->count() > 0){
          return response()->json(['status'=>1,'message'=>'','data'=>$result]);                    
      }else{
          return response()->json(['status'=>$status,'message'=>'No Pole type Found','data'=>json_decode("{}")]);                    
      }   
    }catch(Exception $e){
      return response()->json(['status'=>$status,'message'=>'Exception Error','data'=>json_decode("{}")]);                    
    }
            
  }

  public function getLocationByDivisionId(Request $request){
    
    try{
      $status = 0;
      $message = "";    
      
      $validator = Validator::make($request->all(), [
          'division_id' => 'required',                                                    
      ]);           
      if($validator->fails()){
        $error = json_decode(json_encode($validator->errors()));
        if(isset($error->division_id)){
          $message = $error->division_id[0];
        }
        return response()->json(["status"=>$status,"message"=>$message,"data"=>json_decode("{}")]);
      } 

      $division_id = $request->division_id;

      $result = SiteData::getLocationByDivision($division_id);
      if($result->count() > 0){
          return response()->json(['status'=>1,'message'=>'','data'=>$result]);                    
      }else{
          return response()->json(['status'=>$status,'message'=>'No location Found','data'=>json_decode("{}")]);                    
      }   
    }catch(Exception $e){
      return response()->json(['status'=>$status,'message'=>'Exception Error','data'=>json_decode("{}")]);                    
    }
            
  }

  public function getFeeders(Request $request){
    
    try{
      $status = 0;
      $message = "";    
      
      
      $result = Feeder::orderBy('name','asc')->get();
      if($result->count() > 0){
          return response()->json(['status'=>1,'message'=>'','data'=>$result]);                    
      }else{
          return response()->json(['status'=>$status,'message'=>'No feeder type Found','data'=>json_decode("{}")]);                    
      }   
    }catch(Exception $e){
      return response()->json(['status'=>$status,'message'=>'Exception Error','data'=>json_decode("{}")]);                    
    }
            
  }

  public function getFeederByLocationId(Request $request){
    
    try{
      $status = 0;
      $message = "";    
      
      $validator = Validator::make($request->all(), [
          'location_id' => 'required',                                                    
      ]);           
      if($validator->fails()){
        $error = json_decode(json_encode($validator->errors()));
        if(isset($error->location_id)){
          $message = $error->location_id[0];
        }
        return response()->json(["status"=>$status,"message"=>$message,"data"=>json_decode("{}")]);
      } 

      $location_id = $request->location_id;

      $result = SiteData::getFeederByLocation($location_id);
      if($result->count() > 0){
          return response()->json(['status'=>1,'message'=>'','data'=>$result]);                    
      }else{
          return response()->json(['status'=>$status,'message'=>'No data Found','data'=>json_decode("{}")]);                    
      }   
    }catch(Exception $e){
      return response()->json(['status'=>$status,'message'=>'Exception Error','data'=>json_decode("{}")]);                    
    }
            
  }

  public function getDataByPole(Request $request){
    
    try{
      $status = 0;
      $message = "";    
      
      $validator = Validator::make($request->all(), [
          'pole' => 'required',                                                    
      ]);           
      if($validator->fails()){
        $error = json_decode(json_encode($validator->errors()));
        if(isset($error->pole)){
          $message = $error->pole[0];
        }
        return response()->json(["status"=>$status,"message"=>$message,"data"=>json_decode("{}")]);
      } 

      $pole = $request->pole;

      $result = Siteinfo::getDataByPole($pole);
      if($result->count() > 0){
          return response()->json(['status'=>1,'message'=>'','data'=>$result]);                    
      }else{
          return response()->json(['status'=>$status,'message'=>'No data Found','data'=>json_decode("{}")]);                    
      }   
    }catch(Exception $e){
      return response()->json(['status'=>$status,'message'=>'Exception Error','data'=>json_decode("{}")]);                    
    }
            
  }

   
}

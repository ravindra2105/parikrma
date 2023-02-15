<?php

namespace App\Http\Controllers\v1;

use App\User;
use App\Site;
use App\Siteinfo;
use App\LoginHistory;
use App\ManpowerAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Traits\SendMail;
use Config;
use App\Common\Utility;
use App\Classes\UploadFile;
use Mail;


class UserController extends Controller
{
    use SendMail;
    public function authenticate(Request $request)
    {
        
        $status = 0;
        $message = "";
        
        //if($validator->fails()){
        //  return response()->json(["status"=>$status,"message"=>"Please provide all mandatory fields","data"=>json_decode("{}")]);
       // }

        $request->merge([
          'email' => $request->email,
          'password' => $request->password
        ]);                
        $credentials = $request->only('email', 'password');                
        try {
          $myTTL = 43200; //minutes
          JWTAuth::factory()->setTTL($myTTL);            
            if (! $token = JWTAuth::attempt($credentials)) {            
                $message = 'invalid_credentials';                
                return response()->json(['status'=>$status,'message'=>$message,'data'=>json_decode("{}")]);
            }
        } catch (JWTException $e) {
            $message = 'could_not_create_token';
            return response()->json(['status'=>$status,'message'=>$message,'data'=>json_decode("{}")]);            
        }        
        $user  = JWTAuth::user();
        
        $user->token = $token;
        $status = 1;        
        
        
        $siteInfo = Site::getSiteInfoByUser($user->id);
        $userinfo = User::getUserAllInfoByUserId($user->id);
        $adminUsr = User::getAdminUser($user->id);
        $returnData = ['user'=>$user,'user_details'=>$userinfo,'siteInfo'=>$siteInfo,'admin'=>$adminUsr];

        return response()->json(['status'=>$status,'message'=>$message,'data'=>$returnData]);
    }

    public function apilogout(Request $request){
      
      try{        

        $user  = JWTAuth::user();

        $login_history = new LoginHistory(); 
        $login_history->user_id = $user->id;
        $login_history->entry_type = 'logout';
        if(isset($request->ostype)){
          $login_history->ostype = $request->ostype;
        }
        
        if(isset($request->lat) && isset($request->lng)){
          $arr = [$request->lat,$request->lng];
          $login_history->geo_point = implode(',',$arr);
        }

        if(isset($_FILES['file']['name']) && count($_FILES['file']['name'])>0) {
            for($k=0; $k < count($_FILES['file']['name']); $k++) {
              $upload_handler = new UploadFile();
              $path = public_path('uploads/attendances'); 
              $data = $upload_handler->multiUpload($k,$path,'attendances');
              $res = json_decode($data);
              if($res->status=='ok'){                
                $login_history->image = $res->path;
                $login_history->file_path = $res->img_path;                
              }
            }
        }
        $login_history->mark_datetime = date('Y-m-d H:i:s');
        $login_history->created_at = date('Y-m-d H:i:s');
        $login_history->save();

        JWTAuth::invalidate(JWTAuth::parseToken()); 
        //JWTAuth::setToken($token)->invalidate();
        return response()->json(['status'=>1,'message'=>'','data'=>json_decode("{}")]);
      }catch(Exception $e){
        return response()->json(['status'=>0,'message'=>'Not able to logout','data'=>json_decode("{}")]);
      }
      
    }
    

    
    
    public function getAuthenticatedUser() { 
         $status = 0;   
        try {

                if (! $user = JWTAuth::parseToken()->authenticate()) {
                  //return response()->json(['user_not_found'], 404);
                  return response()->json(['status'=>$status,'message'=>'user_not_found','data'=>json_decode("{}")]);
                }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            //return response()->json(['token_expired'], $e->getStatusCode());
            return response()->json(['status'=>$status,'message'=>'token_expired','data'=>json_decode("{}")]);

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

          //return response()->json(['token_invalid'], $e->getStatusCode());
          return response()->json(['status'=>$status,'message'=>'token_invalid','data'=>json_decode("{}")]);

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
          return response()->json(['status'=>$status,'message'=>'token_absent','data'=>json_decode("{}")]);
          //return response()->json(['token_absent'], $e->getStatusCode());
        }
        $status = 1;
        return response()->json(compact('user'));
   }
     
   public function getAttendance(Request $request){
    try{        

        $user  = JWTAuth::user();
        
        if(isset($request->date)){
          $data = LoginHistory::getAttendanceByUserWithDate($user->id,$request->date)->paginate($this->paging);
        }else{          
          $data = LoginHistory::getAttendanceByUser($user->id)->paginate($this->paging);
        }
        
                
        return response()->json(['status'=>1,'message'=>'','data'=>$data]);
      }catch(Exception $e){
        return response()->json(['status'=>0,'message'=>'Not able to logout','data'=>json_decode("{}")]);
      }
   }

   public function getManPower(Request $request){
    try{        

        $user  = JWTAuth::user();
        $type = '';
        
        $validator = Validator::make($request->all(), [
            'site_id' => 'required',                 
        ]);
                      
        if($validator->fails()){
            //Log::debug(['add event validation failed',$request->all()]);
            return response()->json(['status'=>$status,'message'=>'invalid data set','data'=>json_decode("{}")]);
            
        }

        if(isset($request->type)){
          $type = $request->type;
        }

        $data = User::getManPower($type, $request->site_id)->paginate($this->paging);
                        
        return response()->json(['status'=>1,'message'=>'','data'=>$data]);
      }catch(Exception $e){
        return response()->json(['status'=>0,'message'=>'Not able to logout','data'=>json_decode("{}")]);
      }
   }

   public function markManpowerAttendance(Request $request){

    try{        

      $user  = JWTAuth::user();
      
      //echo '<pre>';print_r($request->all()); die;    

      if(count($request->user)>0){
        foreach($request->user as $k=>$v){        
            $data[$k]['user_id'] = $v['user_id'];   
            $data[$k]['attendance'] = $v['attendance'];
        }                        
        if(ManpowerAttendance::insert($data)){
          return response()->json(['status'=>1,'message'=>'','data'=>$data]);    
        }else{
          return response()->json(['status'=>0,'message'=>'Not able to insert','data'=>json_decode("{}")]);
        } 
      }       
                            
    }catch(Exception $e){
      return response()->json(['status'=>0,'message'=>'Not able to logout','data'=>json_decode("{}")]);
    }

    
   }

   public function addScreenShot(Request $request){
    try{        
      $user  = JWTAuth::user();      
      //echo '<pre>';print_r($request->all()); die;    
      $status = 0;
      $message = 'Photo not uploaded';
      if(isset($user->id)){
        $login_history = LoginHistory::where('user_id',$user->id)
        ->where(DB::raw('DATE(mark_datetime)'),date('Y-m-d'))
        ->first(); 
        if(!isset($login_history->id)){
          return response()->json(['status'=>0,'message'=>'No Record found','data'=>json_decode("{}")]);          
        }
        if(isset($_FILES['file']['name']) && count($_FILES['file']['name'])>0) {
            for($k=0; $k < count($_FILES['file']['name']); $k++) {
              $upload_handler = new UploadFile();
              $path = public_path('uploads/attendances'); 
              $data = $upload_handler->multiUpload($k,$path,'attendances');
              $res = json_decode($data);
              if($res->status=='ok'){                
                $login_history->image = $res->path;
                $login_history->file_path = $res->img_path;                
              }
            }
            $status = 1;
            $message = 'screenshot added successfully';
            $login_history->save();     
        }        
                                       
        return response()->json(['status'=>$status,'message'=>$message,'data'=>json_decode("{}")]);
      }       
                            
    }catch(Exception $e){
      return response()->json(['status'=>0,'message'=>'Not able to logout','data'=>json_decode("{}")]);
    }

    
   }

   public function changePassword(Request $request){
    try{
        
      $status = 0;
            $message = "";
          
            
            $user  = JWTAuth::user();  

            $validator = Validator::make($request->all(), [
                'old_password' => 'required',                                
                'new_password' => 'min:6|required_with:password_confirmation|same:password_confirmation',                                
                'password_confirmation' => 'required|min:6',                                
            ]);           
            if($validator->fails()){
              $error = json_decode(json_encode($validator->errors()));
              if(isset($error->old_password)){
                $message = $error->old_password[0];
              }else if(isset($error->new_password)){
                $message = $error->new_password[0];
              }else if(isset($error->password_confirmation)){
                $message = $error->password_confirmation[0];
              }
              return response()->json(["status"=>$status,"message"=>$message,"data"=>json_decode("{}")]);
            } 

            if(!Hash::check($request->old_password, $user->password)){
              return response()->json(['status'=>$status,'message'=>'old password incorrect','data'=>json_decode("{}")]);
            }else{            
              User::where('email', $user->email)->update(['password'=>Hash::make($request->new_password)]);
              
              return response()->json(['status'=>1,'message'=>$message, 'data'=>json_decode("{}")]);
            }            

            return response()->json(['status'=>1,
            'message'=>$message,                                
            'data'=>[]
            ]);
            
                                          
        }catch(Exception $e){
      
            return response()->json(['status'=>$status,'message'=>$message,'data'=>json_decode("{}")]);    
        }   
    }

      /**
     * Edit event method
     * @return success or error
     * 
     * */
    public function editMyProfile(Request $request){
      
      try{
        $status = 0;
        $message = "";
              
        $user  = JWTAuth::user();
        

        $user->id = $user->id;
        $user->name = (isset($request->name)) ? $request->name : $user->name;
        $user->phone = (isset($request->phone)) ? $request->phone : $user->phone;

        if(isset($_FILES['file']['name']) && count($_FILES['file']['name'])>0) {
          for($k=0; $k < count($_FILES['file']['name']); $k++) {
            $upload_handler = new UploadFile();
            $path = public_path('uploads/users'); 
            $data = $upload_handler->multiUpload($k,$path,'users');
            $res = json_decode($data);
            if($res->status=='ok'){                
              $user->image = $res->path;
              $user->file_path = $res->img_path;                
            }
          }
      }
            
        if(!$user->save()){          
            return response()->json(['status'=>$status,'message'=>'Unable to save','data'=>$user]);                    
        }else{          
            return response()->json(['status'=>1,'message'=>'Profile updated successfully','data'=>$user]);                    
        }   
      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'User update Error','data'=>json_decode("{}")]);                    
      }
              
    }

    public function getManPowerAttendance(Request $request){
      try{
        $status = 0;
        $message = "";
              
        $user  = JWTAuth::user();
        
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
        $date = (isset($request->date)) ? $request->date : "";
        $type = (isset($request->type)) ? $request->type : "";

        $data = User::getManPowerAttendance($user->id,$request->site_id,$date,$type)->get();

        return response()->json(['status'=>1,'message'=>'','data'=>$data]);                    
      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'Error','data'=>json_decode("{}")]);                    
      }
    }
    
    public function addInfo(Request $request){
        try{        
          $user  = JWTAuth::user();      
        //   $site = Site::where('id','<>',0)->get();
        //   foreach($site as $k=>$v){
        //       if (!is_dir(public_path().'/uploads/info/'.$v->alias_name)) {
        //         mkdir(public_path().'/uploads/info/'.$v->alias_name, 0777, true);
        //       }
        //   }
          
        //   die;
          //echo '<pre>';print_r($request->all()); die;    
          $status = 0;
          $message = '';
          
          $validator = Validator::make($request->all(), [
            'site_id' => 'required',
            'location_id'=>'required',
            'feeder_id'=>'required',
            'pole'=>'required',
            'type_of_activity'=>'required',
            'phone'=> 'required',
            'lat'=>'required',
            'lng'=>'required',
            'division_id'=>'required',
            'poletype_id'=>'required',
            'device_name'=>'required',
            'img_type'=>'required'
          ]);   
            
          
          if($validator->fails()){
              $error = json_decode(json_encode($validator->errors()));
              if(isset($error->site_id)){
                $message = $error->site_id[0];
              }else if(isset($error->phone)){
                $message = $error->phone[0];
              }else if(isset($error->lat)){
                $message = $error->lat[0];
              }else if(isset($error->lng)){
                $message = $error->lng[0];
              }else if(isset($error->division_id)){
                $message = $error->division_id[0];
              }else if(isset($error->poletype_id)){
                $message = $error->poletype_id[0];
              }else if(isset($error->device_name)){
                $message = $error->device_name[0];
              }else if(isset($error->img_type)){
                $message = $error->img_type[0];
              }else if(isset($error->location_id)){
                $message = $error->location_id[0];
              }else if(isset($error->feeder_id)){
                $message = $error->feeder_id[0];
              }else if(isset($error->pole)){
                $message = $error->pole[0];
              }else if(isset($error->type_of_activity)){
                $message = $error->type_of_activity[0];
              }


              
              
              
              return response()->json(["status"=>$status,"message"=>$message,"data"=>json_decode("{}")]);
          } 
            
          if(isset($user->id)){
            $siteinfo = new Siteinfo();
            $siteinfo->site_id = $request->site_id;
            $siteinfo->division_id = $request->division_id;
            $siteinfo->location_id = $request->location_id;
            $siteinfo->feeder_id = $request->feeder_id;
            $siteinfo->user_id = $user->id;
            $siteinfo->division_id = $request->division_id;
            $siteinfo->lat = $request->lat;
            $siteinfo->lng = $request->lng;
            $siteinfo->poletype_id = $request->poletype_id;            
            $siteinfo->pole = $request->pole;   
            $siteinfo->device_name = $request->device_name;
            $siteinfo->img_type = $request->img_type;
            $siteinfo->type_of_activity = $request->type_of_activity;
            
            
            $site = Site::where('id',$request->site_id)->first();
            if(!isset($site->id)){
                return response()->json(['status'=>$status,'message'=>'Site info not fount','data'=>json_decode("{}")]);
            }
            
            if(isset($_FILES['file']['name'])) {
                
              $upload_handler = new UploadFile();
              $path = public_path('uploads/info/'.$site->alias_name); 
              $data = $upload_handler->upload($path,'info/'.$site->alias_name);
              $res = json_decode($data);
              if($res->status=='ok'){                
                $siteinfo->image = $res->path;
                $siteinfo->file_path = $res->img_path;                
              }
            }        
            
            if($siteinfo->save()){
                return response()->json(['status'=>1,'message'=>$message,'data'=>json_decode("{}")]);
            }else{
                $message = 'Unable to store data';
                return response()->json(['status'=>$status,'message'=>$message,'data'=>json_decode("{}")]);
            }                               
            
          }       
                                
        }catch(Exception $e){
          return response()->json(['status'=>0,'message'=>'Not able to logout','data'=>json_decode("{}")]);
        }

   }
   
   
   public function uploaddata(Request $request){
      try{
        $status = 0;
        $message = "";
        $fileName = uniqid().".csv";
        $file_path = public_path()."/csvs/".$fileName;      
        $datas = $request->data;
        
        $handle = fopen($file_path, "a");
        

        //file_put_contents($file_path, $datas,FILE_APPEND);     
        $csvArr= ["CODE","ID","LATTTUDE","LONGITUDE","PINCODE","TIME"];
        fputcsv($handle, $csvArr);
        
        if(count($datas)>0){
            foreach($datas as $k=>$v){
                
                $d = [$v['code'],$v['id'],$v['latitude'],$v['longitude'],$v['pincode'],$v['time']];
                fputcsv($handle, $d);
            }
            
        }
        fclose($handle);
        
        
        $data = [];          
        //$data['to_email'] = 'hemant.gupta@techconfer.com';
        //$data['cc'] = 'itsjustravisoni@gmail.com';
        $data['to_email'] = 'rmitra@mcnroe.com';
        $data['cc'] = 'mcbizhelpdesk@mcnroe.com';
        $data['name'] = 'ravindra';
        $data['file'] = public_path('csvs/').$fileName;
        $data['from'] = 'Support@trafiksol.com';
        $data['supportEmail'] = 'support@trafiksol.com';                    
        $data['subject'] = 'Data report'; 
        $data['message1'] = 'Please find the report with an attachment'; 

        if($this->SendMail($data,'csvmail')){                              
            return response()->json(["status"=>1,"message"=>"Report send successfully","data"=>json_decode("{}")]);                 
        }  
                
                
        return response()->json(['status'=>0,'message'=>'Report not send','data'=>json_decode("{}")]);                    
      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'Error','data'=>json_decode("{}")]);                    
      }
    }
   
   

}
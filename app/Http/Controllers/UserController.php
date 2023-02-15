<?php
    
namespace App\Http\Controllers;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Client;
use App\Site;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use App\Http\Controllers\Traits\SendMail;
use Tzsk\Collage\MakeCollage;
use Mail;
    
class UserController extends Controller
{    
    use SendMail;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $data = User::orderBy('id','DESC')->paginate(5);
        return view('users.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function ajaxData(Request $request){
    
        $draw = (isset($request->data["draw"])) ? ($request->data["draw"]) : "1";
        $response = [
          "recordsTotal" => "",
          "recordsFiltered" => "",
          "data" => "",
          "success" => 0,
          "msg" => ""
        ];
        try {
            
            $start = ($request->start) ? $request->start : 0;
            $end = ($request->length) ? $request->length : 10;
            $search = ($request->search['value']) ? $request->search['value'] : '';
            //echo 'ddd';die;
            $cond[] = [];
            
            //echo '<pre>'; print_r($users); die; categoryFilter

            $obj = User::select('users.*',DB::raw('group_concat(roles.name) as rolename'))
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')      
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')     
            ->groupBy('users.id');
            
            if ($request->search['value'] != "") {            
              $obj = $obj->where('users.name','LIKE',"%".$search."%");
            } 

            if(isset($request->order[0]['column']) && $request->order[0]['column']==0){
                $sort = $request->order[0]['dir'];
                $obj = $obj->orderBy('users.name',$sort);
            }

           
            if(isset($request->order[0]['column']) && $request->order[0]['column']==1){
                $sort = $request->order[0]['dir'];
                $obj = $obj->orderBy('users.email',$sort);
            }

            if(isset($request->order[0]['column']) && $request->order[0]['column']==2){
                $sort = $request->order[0]['dir'];
                $obj = $obj->orderBy('users.phone',$sort);
            }

            if(isset($request->order[0]['column']) && $request->order[0]['column']==3){
                $sort = $request->order[0]['dir'];
                $obj = $obj->orderBy('roles.name',$sort);
            }
   
            $total = $obj->count();
            if($end==-1){
              $obj = $obj->get();
            }else{
              $obj = $obj->skip($start)->take($end)->get();
            }
            
            $response["recordsFiltered"] = $total;
            $response["recordsTotal"] = $total;
            //response["draw"] = draw;
            $response["success"] = 1;
            $response["data"] = $obj;
            
          } catch (Exception $e) {    
            
          }
        
   
        return response($response);
      }

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('users.create',compact('roles'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|numeric|digits:10',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
    
        $user = User::create($input);
        $user->assignRole($request->input('roles'));
    
        return redirect()->route('users.index')
                        ->with('success','User created successfully');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
    
        return view('users.edit',compact('user','roles','userRole'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'nullable|numeric|digits:10',
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            
            $input = $request->except(['password']);
        }
    
        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
    
        $user->assignRole($request->input('roles'));
    
        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }


    public function listsite(Request $request){
        try{        
            
          $selected_sites = DB::table('site_access')->where('user_id',$request->id)->get();
          
          $sitelist = Site::select('sites.*')->where('sites.id','<>',0)->get();
          if($sitelist->count()){
              foreach($sitelist as $k=>$v){
                  
                  $sitelist[$k]->checked = false;
                  
                  if($selected_sites->count()){
                      foreach($selected_sites as $k1=>$v1){
                          if($v->id == $v1->site_id){
                              $sitelist[$k]->checked = true;
                          }
                      }
                  }
                  
                      
              }
          }
          
          
          return response()->json(['status'=>1,'message'=>'','data'=>$sitelist]);    
                                
        }catch(Exception $e){
          return response()->json(['status'=>0,'message'=>'Not able to get value','data'=>json_decode("{}")]);
        }

    }
    
    public function assignsiteaccess(Request $request){
        try{        

          $sitelist = DB::table('site_access')->where('user_id',$request->user_id_hidden)->delete();
          
          if(isset($request->site_id)){
              
              $data = [];
              foreach($request->site_id as $k=>$v){
                $data[] = ['site_id'=>$v, 'user_id'=> $request->user_id_hidden];
              }
              
              DB::table('site_access')->insert($data);
          }
          return response()->json(['status'=>1,'message'=>'','data'=>json_decode("{}")]);    
                                
        }catch(Exception $e){
          return response()->json(['status'=>0,'message'=>'Not able to get value','data'=>json_decode("{}")]);
        }

    }

    public function collage(Request $request){

        $collage = new MakeCollage();       
        $images = [
            // List of images
            public_path().'/images/1.jpg',
            public_path().'/images/2.jpg'
        ];
         $image = $collage->make(400, 200)->padding(10)->background('#fff')->from($images, function($alignment) {
            $alignment->vertical(); // Default, no need to have the Closure at all.            
            //$alignment->horizontal();
        });
        $image->save(public_path().'/uploads/lasjflsd.jpg');

        //return view('collage', ['name' => 'James']);
        //return view('map', ['name' => 'James']);
        
        
    }
    

}

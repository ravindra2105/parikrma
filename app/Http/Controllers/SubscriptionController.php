<?php
    
namespace App\Http\Controllers;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Subscription;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use App\Http\Controllers\Traits\SendMail;
use Tzsk\Collage\MakeCollage;
use Mail;
    
class SubscriptionController extends Controller
{    
    use SendMail;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $data = Subscription::orderBy('id','DESC')->paginate(5);
        return view('subscriptions.index',compact('data'))
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

            $obj = Subscription::select('subscriptions.*');
            
            if ($request->search['value'] != "") {            
              $obj = $obj->where('subscriptions.name','LIKE',"%".$search."%");
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
        
        return view('subscriptions.create',[]);
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
            'price' => 'required'
        ]);
    
        $input = $request->all();
    
    
        $subs = Subscription::create($input);
    
    
        return redirect()->route('subscriptions.index')
                        ->with('success','Subscription created successfully');
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
        $subscription = Subscription::find($id);
        
    
        return view('subscriptions.edit',compact('subscription'));
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
            'price' => 'required',
            
        ]);
    
        $input = $request->all();
        
    
        $subscription = Subscription::find($id);
        $subscription->update($input);
        
        return redirect()->route('subscriptions.index')
                        ->with('success','Subscription updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        Subscription::find($id)->delete();
        return redirect()->route('subscriptions.index')
                        ->with('success','Subscription deleted successfully');
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

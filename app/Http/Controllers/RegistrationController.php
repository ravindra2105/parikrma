<?php

namespace App\Http\Controllers;

use App\Registration;
use App\User;
use App\Role;
use App\Country;
use App\RegType;
use App\Subscription;
use App\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;
use PDF;
use App\Classes\UploadFile;
use App\Jobs\CsvUploadProcess;
use Illuminate\Support\Facades\URL;
use Tzsk\Collage\MakeCollage;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:site-list|site-create|site-edit|site-delete', ['only' => ['index','show']]);
         $this->middleware('permission:site-create', ['only' => ['create','store']]);
         $this->middleware('permission:site-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:site-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        
        $registrations = Registration::latest()->paginate(10);
        $reg_types = RegType::getRegTypeListDD();
        return view('registrations.index',compact('registrations','reg_types'
        ));
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

            $obj = Registration::select('registrations.*',
            'reg_types.name as regtype_name','users.name as added_user')            
            ->join('reg_types','reg_types.id','=','registrations.reg_type_id')
            ->leftJoin('users','users.id','=','registrations.added_by')
            ->groupBy('registrations.id');

            if ($request->search['value'] != "") {
              $obj = $obj->where('registrations.name','LIKE',"%".$search."%");
              $obj = $obj->orWhere('registrations.mobile','LIKE',"%".$search."%");
              $obj = $obj->orWhere('registrations.city','LIKE',"%".$search."%");
              $obj = $obj->orWhere('reg_types.name','LIKE',"%".$search."%");
              
            }

            if(isset($request->order[0]['column']) && $request->order[0]['column']==1){
                $sort = $request->order[0]['dir'];
                $obj = $obj->orderBy('registrations.name',$sort);
            }

            if(isset($request->order[0]['column']) && $request->order[0]['column']==2){
                $sort = $request->order[0]['dir'];
                $obj = $obj->orderBy('reg_types.name',$sort);
            }

            if(isset($request->order[0]['column']) && $request->order[0]['column']==3){
                $sort = $request->order[0]['dir'];
                $obj = $obj->orderBy('registrations.mobile',$sort);
            }
                        
            if(isset($request->order[0]['column']) && $request->order[0]['column']==4){
                $sort = $request->order[0]['dir'];
                $obj = $obj->orderBy('registrations.city',$sort);
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
        
        
        $reg_types = RegType::getRegTypeListDD();
        $subscriptions = Subscription::getSubscriptionListDD();
        
        $country = Country::getAllCountry();
        return view('registrations.create',compact(
            'reg_types',
            'country',
            'subscriptions'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //echo '<pre>'; print_r($request->all()); die;
           
        try{
           // DB::beginTransaction();

            request()->validate([
                'name' => 'required',
                'reg_type_id' => 'required',                
                'city' => 'required',
                'state_id' => 'required',
                'country_id' => 'required',
            ]);
            $added_by = Auth::user()->id;

            $request->request->add(['added_by'=>$added_by]);
            $insertQuery = Registration::create($request->all());
        

            return redirect()->route('registrations.index')
                            ->with('success','Registration created successfully.');

        }catch(Exception $e){
            //DB::rollBack();
            abort(500, $e->message());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Site  $Site
     * @return \Illuminate\Http\Response
     */
    public function show(Site $site)
    {
        
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Registration $registration)
    {
        //dd($site->siteimages()->count());
        $reg_types = RegType::getRegTypeListDD();       
        $country = Country::getAllCountry();
        $state = State::getStateByCountryDD($registration->country_id);        

        $subscriptions = Subscription::getSubscriptionListDD();

        return view('registrations.edit',compact('registration',
        'reg_types','country','state','subscriptions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Site  $Site
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Registration $registration)
    {
        try{
            //DB::beginTransaction();
            
            request()->validate([
                'name' => 'required',                
                'city' => 'required',
                'state_id' => 'required',
                'country_id' => 'required',
            ]);
            
            $added_by = Auth::user()->id;
            
            $request->request->add(['added_by'=>$added_by]);
                
            $registration->update($request->all());


            return redirect()->route('registrations.index')
                            ->with('success','Registration updated successfully');

        }catch(Exception $e){
            //DB::rollBack();
            abort(500, $e->message());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Site  $Site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Registration $registration)
    {
        $registration->delete();

        return redirect()->route('registrations.index')
                        ->with('success','Registration deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Site  $Site
     * @return \Illuminate\Http\Response
     */
    public function icard(Request $request, $id)
    {
        $data = Registration::findOrFail($id);

        return view('registrations.icard',compact('data'));
        
    }

    
    
}

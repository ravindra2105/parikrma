<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Traits\Common;
use DB;
use App\RegType;
use App\Country;




use Illuminate\Http\Request;

class HomeController extends Controller
{
    use Common;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
        $this->middleware('auth');
        $sites = DB::table('sites')->get();
        return view('home',compact('sites'));
    }

    public function add()
    {
        $reg_types = RegType::getRegTypeListDD();
        $country = Country::getAllCountry();
        return view('front.create',compact('reg_types','country'));
    }
    
}

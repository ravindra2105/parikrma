<?php
  
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Siteinfo;

class Registration extends Model
{

    public function country(){
        return $this->belongsTo('App\Country');
    }

    public function state(){
        return $this->belongsTo('App\State');
    }

    public function subscription(){
        return $this->belongsTo('App\Subscription');
    }
    
    public function reg_type(){
        return $this->belongsTo('App\RegType');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','reg_type_id','age','address','mobile','email','qualification','profession','diksha_guru',
        'city','state_id','zip','country_id','img_url','added_by'
    ];

    
    
}

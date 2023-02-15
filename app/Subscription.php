<?php
 
namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;  

class Subscription extends Model
{
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','description','price'
    ];

    
    
    public static function getSubscriptionListDD(){
		return self::where('id','<>',0)->pluck('name','id')->sortBy('name');
	}
}

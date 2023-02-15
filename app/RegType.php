<?php
 
namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;  

class RegType extends Model
{
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];



    public static function getRegTypeListDD(){
		return self::where('id','<>',0)->pluck('name','id')->sortBy('name');
	}
    
}

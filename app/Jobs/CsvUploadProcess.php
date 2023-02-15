<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CsvUploadProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $data = [];
    private $filename = '';
    private $flag = 0;
    public function __construct($data,$filename)
    {
        $this->data = $data;
        $this->filename = $filename;        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        
        $file_handle = fopen($this->filename,"a");

       // fputcsv($file_handle, ["CODE","LATITUDE","LONGITUDE","LAST MODIFIED DATE","ID","POSTAL CODE"]);
        
        foreach($this->data as $k=>$v){
            
            $vArr = explode(",",$v);
            if(isset($vArr[1]) && isset($vArr[2])){
                $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$vArr[1].','.$vArr[2].'&key=AIzaSyDDwpXtcWAE-2XSzQG-fONHeLrcWIoWuXY';							            

                $response = file_get_contents($url);
                $response = json_decode($response);
                if(isset($response->results[0]->address_components)){
                    foreach($response->results[0]->address_components as $key=>$value){
                        if(isset($value->types[0]) && $value->types[0]=="postal_code"){
                            $vArr[5] = $value->short_name; 
                            fputcsv($file_handle, $vArr);                           
                        }
                    }
                }else{
                    fputcsv($file_handle, ["ID","NAME","TTA","DDD","BBB"]);                           
                }
            }
            
        }
        fclose($file_handle);
        
    }

    
}

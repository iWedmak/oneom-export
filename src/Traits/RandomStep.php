<?php namespace iWedmak\Export\Traits;

use Cache;

trait RandomStep
{
    
    public function random_step($job, $cache_name, $relise_time=30, $number=false, $of_what='minutes')
    {
        if(!$number)
        {
            $number=rand(1,3);
        }
        
        if (!Cache::has($cache_name)) {
            Cache::forever($cache_name, strtotime('now'));
        }
        
        $last = Cache::get($cache_name);
        //pre(date('c',$last));
        if($last>=strtotime('-'.$number.' '.$of_what))
        {
            $job->release($relise_time);
            //pre('too soon for '.$cache_name);
            die();
        }
        //pre('time for '.$cache_name);
        Cache::forever($cache_name, strtotime('now'));
    }
    
}

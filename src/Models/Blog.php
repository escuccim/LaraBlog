<?php

namespace Escuccim\LaraBlog\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Blog extends Model
{
    protected $fillable = [
    	'user_id',
    	'title',
    	'slug',
    	'body',
    	'published_at',
    	'published',
    ];
    
    protected $dates = ['published_at'];
    
    public static function getAll($admin = false){
    	if(!$admin){
	    	$result = Cache::remember('blog_posts', 120, function(){
	    		return Blog::latest('published_at')->orderBy('id', 'desc')->published()->paginate(5);
	    	});
    	} else {
    		$result = Blog::latest('published_at')->orderBy('id', 'desc')->paginate(10);
    	}
    	return $result;
    }
    
    public function scopePublished($query){
    	$query->where('published_at', '<=', Carbon::now())
    		->where('published', 1);
    }
    
    public function scopeUnpublished($query){
    	$query->where('published_at', '>=', Carbon::now())
    		->orWhere('published', 0);
    }
    
    public function setPublishedAtAttribute($date){
    	$this->attributes['published_at'] = Carbon::parse($date);
    }
    
    public function getPublishedAtAttribute($date){
    	return new Carbon($date);
    }
    
    /* An article belongs to a user */
    public function user(){
    	return $this->belongsTo('App\User');
    }
    
    public function comments(){
    	return $this->hasMany('Escuccim\LaraBlog\Models\BlogComment');
    }
    
    /* Get the tags for a given article */
    public function tags() {
    	return $this->belongsToMany('Escuccim\LaraBlog\Models\Tag');
    }
    
    public function getTagListAttribute(){
    	return $this->tags->pluck('id');
    }
    
    public function getBlogStatus(){
    	if($this->published_at > Carbon::now()){
    		return 'panel-danger';
    	} elseif($this->published == 0){
    		return 'panel-warning';
    	} else {
    		return 'panel-default';
    	}
    }
    
    /**
     * This is the original code for blogLinks which does the query and returns the array. I separated it into another function 
     * so that blogLinks can check for this data in the cache and then call this function if necessary.
     * @return array
     */
    private static function getBlogArchives(){
    	$links = DB::table('blogs')
	    	->select(DB::raw('YEAR(published_at) year, MONTH(published_at) month, MONTHNAME(published_at) month_name, title, id, slug'))
	    	// 		Commented out because I don't need counts
	    	->where('published_at', '<=', Carbon::now())
	    	->where('published', 1)
	    	// 			->groupBy('year')
	    	// 			->groupBy('month')
	    	->orderBy('year', 'desc')
	    	->orderBy('month', 'desc')
	    	->get();
    	 
    	$currentYear = 0;
    	$currentMonth = 0;
    	
    	if(count($links)){
	    	foreach($links as $link){
	    		if($currentYear != $link->year){
	    			$archiveArray[$link->year] = [];
	    			$currentYear = $link->year;
	    		}
	    		if($currentMonth != $link->month){
	    			$archiveArray[$link->year][$link->month_name] = [];
	    			$currentMonth = $link->month;
	    		}
	    		$archiveArray[$link->year][$link->month_name][] = ['slug' => $link->slug, 'title' => $link->title];
	    	}
    	} else {
    		$archiveArray = [];
    	}
    	
    	return $archiveArray;
    }
    
    /**
     * Get most links for blog archives menu. Check if the data exists in the cache, if not do the query and store it in the cache.
     * Admin page results are never cached.
     */
    public static function blogLinks(){
    	if(Blog::isUserAdmin()) {
	    	$result = Cache::remember('blog_archives', 120, function(){
	    		return Blog::getBlogArchives();
	    	});
    	} else {
    		$result = Blog::getBlogArchives();
    	}
    	
    	return $result;
    }
    
    /**
     * Get latest 5 posts to display on home page.
     * @return blog array object
     */
	public static function latestPosts(){
		// check if the list is in redis
// 		$latestPosts = Redis::get('blog:latestposts');
		$latestPosts = Cache::get('blog:latestposts');
		
		// if so we need to loop through and convert the dates to strings instead of these stupid objects
		if($latestPosts) {
			$blogs = json_decode($latestPosts);
		} else {
			// else get the list from the DB
			$blogs = Blog::published()->orderBy('published_at', 'desc')->orderBy('id', 'desc')->limit(10)->get()->toArray();
			$encoded = json_encode($blogs);
			
			// put the list into redis
// 			Redis::set('blog:latestposts', $encoded);
			Cache::put('blog:latestposts', $encoded, 1440);
			
			// encode and then decode it so we have the same data format no matter whether the list exists or not
			$blogs = json_decode($encoded);
		}
		
		return $blogs;
	}

	public static function isUserAdmin(){
        if(Auth::guest())
            return false;
        else {
            return (Auth::user()->type);
        }
    }
}

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

    /**
     * Gets all blog posts and returns paginator.
     * @param bool $admin
     * @return mixed
     */
    public static function getAll($admin = false){
        $paglen = config('blog.paginator_length', 5);
    	if(!$admin){
	    	$result = Cache::remember('blog_posts', 120, function(){
	    		return Blog::latest('published_at')->orderBy('id', 'desc')->published()->paginate($paglen);
	    	});
    	} else {
    		$result = Blog::latest('published_at')->orderBy('id', 'desc')->paginate($paglen);
    	}
    	return $result;
    }

    /**
     * Returns only published articles
     * @param $query
     */
    public function scopePublished($query){
    	$query->where('published_at', '<=', Carbon::now())
    		->where('published', 1);
    }

    /**
     * Returns only unpublished blogs -this is not used anymore
     * @param $query
     */
    public function scopeUnpublished($query){
    	$query->where('published_at', '>=', Carbon::now())
    		->orWhere('published', 0);
    }

    /**
     * Sets published_at to carbon instance
     * @param $date
     */
    public function setPublishedAtAttribute($date){
    	$this->attributes['published_at'] = Carbon::parse($date);
    }
    
    public function getPublishedAtAttribute($date){
    	return new Carbon($date);
    }

    /**
     * Assigns a blog to the user who wrote it.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
    	return $this->belongsTo('App\User');
    }

    /**
     * Assigns many comments to a blog
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(){
    	return $this->hasMany('Escuccim\LaraBlog\Models\BlogComment');
    }

    /**
     * Gets the tags that belong to the article
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags() {
    	return $this->belongsToMany('Escuccim\LaraBlog\Models\Tag');
    }

    /**
     * Gets the IDs for the tags for this article for the select list
     * @return mixed
     */
    public function getTagListAttribute(){
    	return $this->tags->pluck('id');
    }

    /**
     * sets the color of the panel in the blog index according to the status of the article
     * @return string
     */
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
     * Actully does the query to get the archives list
     * @return array
     */
    private static function getBlogArchives(){
        $links = DB::table('blogs')
            ->select(DB::raw('YEAR(published_at) year, MONTH(published_at) month, MONTHNAME(published_at) month_name, title, id, slug'))
            ->where('published_at', '<=', Carbon::now())
            ->where('published', 1)
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
     * Get latest 5 posts to display on home page.
     * @return blog array object
     */
	public static function latestPosts(){
		// check if the list is in the cache
		$latestPosts = Cache::get('blog:latestposts');

		// if so decode it
		if($latestPosts) {
			$blogs = json_decode($latestPosts);
		} else {
			// else get the list from the DB
			$blogs = Blog::published()->orderBy('published_at', 'desc')->orderBy('id', 'desc')->limit(10)->get()->toArray();
			$encoded = json_encode($blogs);
			
			// put the list into cache
			Cache::put('blog:latestposts', $encoded, 1440);
			
			// encode and then decode it so the dates aren't objects
			$blogs = json_decode($encoded);
		}
		
		return $blogs;
	}

    /**
     * Check if the user is an admin, used in the views to display add, edit and delete buttons
     * @return bool
     */
	public static function isUserAdmin(){
        if(Auth::guest())
            return false;
        else {
            return (Auth::user()->type);
        }
    }
}

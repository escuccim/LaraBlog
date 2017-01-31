<?php

namespace Escuccim\LaraBlog\Http\Controllers;

use Illuminate\Http\Request;
use Escuccim\LaraBlog\Models\Blog;
use Escuccim\LaraBlog\Models\Tag;
use Escuccim\LaraBlog\Models\BlogComment;
use Illuminate\Support\Facades\Auth;
use Escuccim\LaraBlog\Http\Requests\BlogRequest;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;


class BlogController extends Controller
{
    /**
     * Specify the middleware to prevent unauthorized users from accessing blog admin functions.
     */
	public function __construct(){
 		$this->middleware(config('blog.middleware'))->except(['index', 'show', 'tags', 'comment']);
    }

    /**
     * Main page displays list of blogs.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        // get blogs from DB or cache. If user is admin get from DB, else cache them for an hour
		$blogs = Blog::getAll($this->isUserAdmin());

    	// get links for archive
    	$links = Blog::blogLinks();

    	// set page title
    	$title = 'Blog';
		return view('escuccim::blog/blog', compact('blogs', 'links', 'title'));
	}
	
	/**
	 * Display a blog post from the DB, specified by $slug
	 * @param string $slug
	 * @return view or redirect
	 */
	public function show($slug){
		// only allow admin to get non-published blogs
		if($this->isUserAdmin())
			$blog = Blog::where('slug', $slug)->first();
		else 
			$blog = Blog::where('slug', $slug)->published()->first();
		
		// if no results returned redirect to main page
		if(!$blog)
			return redirect('/blog');
				
		$links = Blog::blogLinks();
		$comments = $blog->comments;

		// set the page title
		$title = $blog->title;
		$description = $blog->title;
		
		return view('escuccim::blog.show', compact('blog', 'links', 'comments', 'title', 'description'));
	}
	
	/**
	 * Display blank form to add a new blog post
	 * @return view
	 */
	public function create(){
		// initialize a blog object to pass to the form, set it to published so we have a default value
		$blog = new Blog();
		$blog->published = 1;
		
		// get the tags and set the tagArray to null
		$tags = Tag::pluck('name', 'id');
		$tagArray = null;
		
		return view('escuccim::blog.create', compact('blog', 'tags', 'tagArray'));
	}
	
	/**
	 * Add a blog to the DB, specified in form data in request
	 * @param BlogRequest $request
	 * @return redirect
	 */
	public function store(BlogRequest $request){		
 		$blog = $this->createBlog($request);
		
 		// update the latest posts list in cache
 		$this->updateLatestPosts();
 		
 		flash()->success('Your blog has been created');
		
		return redirect('blog');
	}
	
	/**
	 * Display edit form of a blog
	 * @param Blog $id
	 * @return view of edit form
	 */
	public function edit($id){
		$blog = Blog::findOrFail($id);
		
		// tags = all tags; tagArray = tags that apply to this blog, to populate select
		$tags = Tag::pluck('name', 'id');
		$tagArray = $blog->tags()->pluck('id')->toArray();
		
		return view('escuccim::blog.edit', compact('blog', 'tags', 'tagArray'));
	}
	
	/**
	 * Update a blog in the DB
	 * @param Blog $id
	 * @param BlogRequest $request
	 * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	public function update($id, BlogRequest $request){
		// get the blog, put the request into data
		$blog = Blog::findOrFail($id);
		$data = $request->all();
		// slugify the slug
        $data['slug'] = str_slug($request->input('slug'));
		// update the blog
        $blog->update($data);

		// sync the tags
		if($request->input('tags'))
			$this->syncTags($blog, $request->input('tags'));

		// update the cache
		$this->updateLatestPosts();	
			
		flash()->success('Your blog post has been edited!');	
		return redirect('blog/' . $blog->slug);
	}
	
	/** 
	 * show all posts with a specified tag, returns the blog view
	 * @param string $name of Tag
	 * @return view
	 **/
	public function tags($name){
        $tag = Tag::where('name', $name)->first();
			
		if(!$tag)
			return redirect('/blog');
	
		// only admin can see non-published blogs
		if($this->isUserAdmin())
			$blogs = $tag->blogs()->latest('published_at')->paginate(config('skooch.blog_results_per_page'));
		else
			$blogs = $tag->blogs()->latest('published_at')->published()->paginate(config('skooch.blog_results_per_page'));
				 
		$links = Blog::blogLinks();
		$title = trans('larablog::blog.labelstitle') . ' ' . $name;
		$description = $name;

		return view('escuccim::blog/blog', compact('blogs', 'links', 'name', 'title', 'description'));
	}

    /**
     * add a new comment, then redirect back to page
    */
    public function comment(Request $request){
        $input['user_id'] = $request->user()->id;
        $input['blog_id'] = $request->input('blog_id');
        $input['body'] = $request->input('body');
        $input['parent_comment_id'] = $request->input('parent_comment_id');

        $slug = $request->input('slug');
        BlogComment::create($input);
        flash()->success(trans('larablog::blog.commentposted'));

        return redirect('/blog/' . $slug);
    }

    public function deleteComment($id, Request $request)
    {
        $comment = BlogComment::where('id', $id)->first();
        $slug = $comment->post->slug;
        $comment->destroy($id);
        return redirect('/blog/' . $slug);
    }

	/**
	 * Delete a blog from the DB
	 * @param int $id
	 * @return redirect
	 */
	public function destroy($id){
		// delete the blog from the DB
		Blog::destroy($id);
        // update the cache list
		$this->updateLatestPosts();
		return redirect('blog');
	}
	
	/** 
	 * recursively check slug to make sure it is unique. If not keep appending '-1' to it until it is.
	 **/
	private function checkSlug($slug){
		if(Blog::where('slug', $slug)->exists()){
			return $this->checkSlug($slug . '-1');
		} else
			return $slug;
	}
	
	/** 
	 * this creates the blog and returns it.
	 * I don't remember why I separated this from the store function, since it's not called from anywhere else
	 **/
	private function createBlog(Request $request){
	    // get user from session so we don't have to change User model
	    $user = Auth::user();
        $data  = $request->all();
        $data['user_id'] = $user->id;

        $slug = str_slug($request->input('slug'));
        $slug = $this->checkSlug($slug);
        $data['slug'] = $slug;

		// create a blog from the form data
		$blog = Blog::create($data);

		// sync tags
		if($request->input('tags'))
			$this->syncTags($blog, $request->input('tags'));
		
		return $blog;
	}
	
	/**
	 * take in tags from drop-down form, check if they exist in DB. if not add them
	 * then update DB with tags for this article
	 */
	private function syncTags(Blog $blog, array $tags) {
		$tagArray = [];
		foreach($tags as $tag){
			$tagId = Tag::where('id', $tag)->first();
			if($tagId){
				$tagArray[] = $tag;
			}
			else {
				$newTag = new Tag();
				$newTag->name = $tag;
				$newTag->save();
				$tagArray[] = $newTag->id;
			}
		}
		$blog->tags()->sync($tagArray);
	}
	
	/**
	 * If cache is on, update the list of latest posts in the cache and update the archives list
	 */
	private function updateLatestPosts(){
	    // if caching is on put the data into the cache
	    if(config('blog.cache')) {
            Blog::latestPosts();
            Blog::blogLinks();
        }
		return true;
	}

	private function isUserAdmin(){
       return config('blog.is_user_admin')();
    }
}

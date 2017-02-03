<?php

namespace Escuccim\LaraBlog\Http\Controllers;

use Escuccim\LaraBlog\Models\Blog;
use App\Http\Controllers\Controller;

class FeedController extends Controller
{
    public function generate(){
		$blogs = Blog::latest('published_at')->orderBy('id', 'desc')->published()->take(20)->get();
		
		$feed = \App::make('feed');
		$feed->title = config('blog.blog_feed_title');
		$feed->description = config('blog.blog_feed_description');
		// $feed->logo = asset('img/logo.png'); //optional
		$feed->link = url('feed');
		$feed->setDateFormat('carbon'); // 'datetime', 'timestamp' or 'carbon'
		$feed->pubdate = $blogs[0]->published_at;
		$feed->lang = 'en';
		$feed->setShortening(true); // true or false
		$feed->setTextLimit(100); // maximum length of description text
		
		foreach ($blogs as $blog)
		{
			// set item's title, author, url, pubdate, description and content
			$feed->add($blog->title, $blog->user->name, url('blog/' . $blog->slug), $blog->published_at, $blog->body, $blog->body);
		}
		
		return $feed->render('rss'); // or atom
	}
}

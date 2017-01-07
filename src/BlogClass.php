<?php

namespace Escuccim\LaraBlog;

use Escuccim\LaraBlog\Models\Blog;

class BlogClass
{
    /**
     * Create a new Skeleton Instance
     */
    public function __construct()
    {
        // constructor body
    }

    public static function getArticle($slug){
        $blog = Blog::where('slug', $slug)->first();
        return $blog;
    }

    public static function getAllArticles($admin = false){
        $blogs = Blog::getAll($admin);
        return $blogs;
    }

    public static function getArchives(){
        $archives = Blog::blogLinks();
        return $archives;
    }

    public static function getComments($slug){
        $blog = Blog::where('slug', $slug)->first();
        $comments = $blog->comments;
        return $comments;
    }
}

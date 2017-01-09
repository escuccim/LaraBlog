# escuccim

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]

This is a simple little Blog for Laravel that I wrote for myself that I am trying to make into a package. It is still under development, but is working so far.

## Install

This package uses Laravel's Auth, so you must have installed that package prior to installing this.

Via Composer
``` bash
$ composer require escuccim/blog
```

Next register the components:

In config/app.php add the following to the providers array:
```
Escuccim\LaraBlog\blogServiceProvider::class,
Collective\Html\HtmlServiceProvider::class,
Laracasts\Flash\FlashServiceProvider::class,
Roumen\Feed\FeedServiceProvider::class,
```

And add the following to the aliases array:
```
'Form' => Collective\Html\FormFacade::class,
'Html' => Collective\Html\HtmlFacade::class,
'Feed' => Roumen\Feed\Feed::class,
```

Registered the middleware in app\Http\Kernel.php to the routeMiddleware array:
```
'admin' => \Escuccim\LaraBlog\Middleware\AdminMiddleware::class,
```

Once you have installed this, run the migrations which will create the necessary database tables and add a few columns to the users table. 

``` bash
php artisan migrate
``` 
The migrations will automatically seed the DB with a default user: admin@example.com with password: password. It will also add a test blog article and a label called test.

The migrations also add two fields to the users table:
 * image contains a URI for an image which is displayed next to comments left by users
 * type specifies whether the user is an admin or not, 1 is admin, 0 is normal user

To load the CSS and JS files this package needs for the editor you need to add the following to your layouts/app.blade.php file in the header section:
```
@yield('header')
```

You can choose to publish the config file and the views with:
``` bash
php artisan vendor:publish
``` 
This will put the config file into config/blog.php where it can be edited. If you do not do this the default values will be used. The config file allows you to change the Blog title and description in the feed, to set the number of results per page returned by the paginator, and to disable caching if you so desire. 

Also note that if you use my views they make use of the Bootstrap CSS files which come out-of-the-box with Laravel.

Note that the comments functionality of the blog will display an image referenced in the Users table if one exists, so if you want to allow users to upload images you need to add functionality for this yourself.


## Usage

This package includes it's own Routes, Models, Controllers, and Views so should work out of the box. To use it just point the browser to /blog. My views extend layouts/app.blade.php and require that you add a line to that file as specified above. They also use Bootstrap CSS for layout and the default JS files that come with Laravel.

If you want to edit my views you can publish them to resources/views/vendor/escuccim with:
``` bash
php artisan vendor:publish
```

If you prefer to write your own views I have provided the following static methods on the BlogClass.
``` php
$blog = new escuccim\LaraBlog\BlogClass();
BlogClass::getAllArticles([isUserAdmin]); 
BlogClass::getArticle('slug');
BlogClass::getArchives();
BlogClass::getComments('slug');
```

Method getAllArticles takes an optional parameter indicating whether the user is an administrator or not. If not it only returns articles which are published, otherwise it returns all articles. This returns a Laravel paginator.

Method getArticle takes in the slug for the article desired and returns all the details, regardless of whether the article is published or not.

Method getComments also takes in the slug and returns all comments, regardless of whether the article is published or not.

Method getArchives returns the data used to construct the archive menu as a nested associative array. 

This package also includes an RSS feed which uses the Roumen\Feed package to generate the feed. This is available at /feed.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email skooch@gmail.com instead of using the issue tracker.

## Credits

- [Eric Scuccimarra][http://ericscuccimarra.com]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/escuccim/blog.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/escuccim/blog/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/escuccim/blog.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/escuccim/blog.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/escuccim/blog.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/escuccim/larablog
[link-travis]: https://travis-ci.org/escuccim/larablog
[link-scrutinizer]: https://scrutinizer-ci.com/g/escuccim/larablog/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/escuccim/larablog
[link-downloads]: https://packagist.org/packages/escuccim/larablog
[link-author]: https://github.com/escuccim


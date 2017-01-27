# escuccim

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]

This is a simple out-of-the-box blog package for Laravel. It supports localization and currently has files for English and French, but adding new languages is easy. Note that this package will localize to the language set in the app config. No means of translating the page per request is provided. If you need this look at escuccim/translate, my package which will translate either based on subdomain or from a session variable.

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

This package uses a middleware to determine what pages the user has access to. By default it uses my middleware which uses a flag in the Users table to indicate whether the user is an admin. To use this, add the following to /app/Http/Kernel.php:
```
'admin' => \Escuccim\LaraBlog\Middleware\AdminMiddleware::class,
```

If you wish to use a different middleware change the 'middleware' key in config/blog.php to be the name of the middleware you want to use.

Run the migrations which will create the necessary database tables and add a few columns to the users table. 

``` bash
php artisan migrate
``` 
The migrations will automatically seed the DB with a default user: admin@example.com with password: password. It will also add a test blog article and a label called test.

The migrations also add two fields to the users table:
 * image contains a URI for an image which is displayed next to comments left by users
 * type specifies whether the user is an admin or not, 1 is admin, 0 is normal user

To load the CSS and JS files this package needs for the editor you need to add the following to your layouts/app.blade.php file in the header section:
```
<script src="/js/app.js"></script>
@yield('header')
```
To enable the Javascript functions of the editors you will need to remove the script reference to app.js from the bottom of the layout file and put it in the header.

You can choose to publish the config file and the views with:
``` bash
php artisan vendor:publish
``` 
The publishable files are separated into three groups:
- lang - includes the language files in English and French and will be published to /resources/lang/vendor/larablog
- config - will publish blog.php to /config/blog.php
- views will publish the views to /resources/views/vendor/escuccim

To only publish one of these groups add --tag=[lang|config|views]

**Notes on Publishable Files:**
My code uses strftime to format the dates, but you will need to set the locale yourself, with setlocale() and will need to make sure you have the locales installed.

The config file allows you to change the Blog title and description in the feed, to set the number of results per page returned by the paginator, and to disable caching if you so desire. 

My views use Bootstrap CSS and some Javascript which comes with Laravel. If you change the CSS or JS files some features may not work.

Note that the comments functionality of the blog will display an image referenced in the Users table if one exists, so if you want to allow users to upload images you need to add functionality for this yourself.


## Usage

This package includes it's own Routes, Models, Controllers, and Views so should work out of the box. To use it just point the browser to /blog. My views extend layouts/app.blade.php and require that you add a line to that file as specified above. They also use Bootstrap CSS for layout and the default JS files that come with Laravel.

If you want to edit my views you can publish them to resources/views/vendor/escuccim with:
``` bash
php artisan vendor:publish
```

This package also includes an RSS feed which uses the Roumen\Feed package to generate the feed. This is available at /feed.

By default the translation will use the value set in your config/app.php. If you want to overwrite the language on a per request basis write the language you want to use to session.locale and that value will replace the config value per request. Note that for time localization you will additionally need to setlocale(). My code currently does this but only for French as this is dependent on you having the proper locales installed on your server.

This package uses a field it adds to the users table called 'type' to determine if the user has permission to add, edit and delete blog posts. If you wish to use your own permission system you can replace the value for 'is_user_admin' in the config file blog.php. This function should return true if the user has permission to do admin tasks and false otherwise.

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

[ico-version]: https://img.shields.io/packagist/v/escuccim/larablog.svg?style=flat-square
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


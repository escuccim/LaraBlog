<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Escuccim\LaraBlog\Models\Blog;
use Escuccim\LaraBlog\Models\Tag;

class BlogTest extends TestCase
{
	use DatabaseTransactions;

	/**
	 * Test blog pages
	 *
	 * @return void
	 */
	public function testExample()
	{
		$this->assertTrue(true);
	}

	public function testBlogPagesAppear (){
		// get a random post from the DB
		$blog = Blog::getAll()->first();
			
		// Check if pages are OK with no user
		$this->visit('/blog')
		->see('Archives')
		->dontSee('Add Blog Post')
		->see($blog->title);
			
		$this->visit('/blog/' . $blog->slug)
		->assertResponseOk()
		->see($blog->title);
			
		// check that people can't see pages they shouldn't be able to
		$response = $this->call('GET', '/blog/create');
		$this->assertEquals(404, $response->status());

		$response = $this->call('GET', '/blog/' . $blog->id . '/edit');
		$this->assertEquals(404, $response->status());
			
		// CHeck if pages are OK with a user
		$user = factory(App\User::class)->create();
			
		$this->actingAs($user)
		->visit('/blog')
		->see('Archives')
		->dontSee('Show/Hide')
		->dontSee('Add Blog Post')
		->see($blog->title);
			
		$this->actingAs($user)
		->visit('/blog/' . $blog->slug)
		->assertResponseOk()
		->see($blog->title);

		// Check if pages are OK with admin
		$user->type = 1;

		$this->actingAs($user)
		->visit('/blog')
		->see('Archives')
		->see('Add Blog Post');

		$this->actingAs($user)
		->visit('/blog/' . $blog->slug)
		->assertResponseOk()
		->see($blog->title);

		$this->visit('/blog/create')
		->assertResponseOk();
		 
		$this->visit('/blog/' . $blog->id . '/edit')
		->assertResponseOk();
		 
		$user->destroy($user->id);
	}

	public function testBlogAdmin(){
		$user = factory(App\User::class)->create();
		$user->type = 1;
		$user->save();
			
		// get id of tag for test
		$test = Tag::where('name', 'test')->first();

		// make a new blog, check that it is made, edit it, check that it is edited, change the date
		$this->actingAs($user)
		->visit('/blog/create')
		->assertResponseOk()
		->see('Write a New Blog Post')
		->type('PHP Unit Test', 'title')
		->type('PHP Unit Test', 'slug')
		->type('PHP Unit Test', 'body')
		->select($test->id, 'tags')
		->press('Add Blog Post')
		->see('Your blog has been created')
		->seePageIs('/blog')
		->see('PHP Unit Test')
		->see('No comments');
		 
		// check that blog page appears
		$this->actingAs($user)
		->visit('/blog/php-unit-test')
		->assertResponseOk()
		->see('PHP Unit Test')
		->see('Edit Blog');

		// make sure it appears in the RSS feed
		$this->visit('/feed')
		->see('PHP Unit Test');

		// test editing of blog
		$this->actingAs($user)
		->visit('/blog')
		->click('PHP Unit Test')
		->see('PHP Unit Test')
		->see('Edit Blog')
		->click('Edit Blog')
		->see('Edit:')
		->type('Test Content', 'body')
		->press('Update Blog')
		->see('Your blog post has been edited!')
		->seePageIs('/blog/php-unit-test')
		->see('PHP Unit Test')
		->click('PHP Unit Test')
		->see('Test Content')
		->click('Edit Blog')
		->type('12/31/2026', 'published_at')
		->press('Update Blog')
		->see('Your blog post has been edited!')
		->see('PHP Unit Test');
			
		// see that blog exists in DB
		$this->seeInDatabase('blogs', [
				'title' => 'PHP Unit Test',
		]);
		 
		// make sure the new blog does NOT appears on the home page
		$this->actingAs($user)
		->visit('/home')
		->dontSee('PHP Unit Test');
		 
		// make sure it doesn't appear in the RSS feed
		$this->visit('/feed')
		->dontSee('PHP Unit Test');
		 
		// proper links appear as admin
		$this->actingAs($user)
		->visit('/blog/php-unit-test')
		->see('PHP Unit Test')
		->see('Edit Blog')
		->see('Delete Blog');
		 
		// check that label is working
		$this->actingAs($user)
		->visit('/blog/labels/test')
		->see('PHP Unit Test');
		 
		// see that it doesn't appear for non-admins
		// it also shouldn't appear because the data is cached
		$user->type = 0;
		$user->save();
		 
		$this->visit('/blog/php-unit-test')
		->dontSee('PHP Unit Test')
		->dontSee('Edit Blog')
		->dontSee('Delete Blog');
			
		$this->actingAs($user)
		->visit('/blog/php-unit-test')
		->dontSee('PHP Unit Test')
		->dontSee('Edit Blog')
		->dontSee('Delete Blog');

		$this->actingAs($user)
		->visit('/blog')
		->dontSee('PHP Unit Test');

		// change back to admin user, reset date on test article
		$user->type = 1;
		$user->save();
		 
		$this->actingAs($user)
		->visit('/blog/php-unit-test')
		->click('Edit Blog')
		->type('12/31/2015', 'published_at')
		->press('Update Blog')
		->see('Your blog post has been edited!')
		->see('PHP Unit Test');

		// check that we can see it as no user
		$user->type = 0;
			
		$this->visit('/blog/php-unit-test')
		->see('PHP Unit Test')
		->dontSee('Edit Blog')
		->dontSee('Delete Blog');

		$user->destroy($user->id);
			
        // flush the cache? maybe not necessary anymore?
	}

	public function testComments(){
		// create a user
		$user = factory(App\User::class)->create();
		// get a random post from the DB
		$blog = Blog::getAll()->first();
			
		// go to its page and leave a comment
		$this->actingAs($user)
		->visit('/blog/' . $blog->slug)
		->see('Leave a Comment')
		->click('Leave a Comment')
		->type('Test comment', 'body')
		->press('Post Comment')
		->see('Your comment has been posted')
		->see('Test comment');
	}
}


abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }
}
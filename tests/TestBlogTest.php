<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Redis;
use Escuccim\LaraBlog\Models\Tag;
use Escuccim\LaraBlog\Models\Blog;
use Escuccim\LaraBlog\Models\BlogComment;

class TestBlogTest extends BrowserKitTest
{
    use DatabaseTransactions;

    /**
     * Test blog pages
     *
     * @return void
     */

    public function testPageStatus ()
    {
        $blog = $this->insertSampleData();

        // Check if pages are OK with no user
        $this->visit('/blog')
            ->assertResponseOk()
            ->see(html_entity_decode(trans('larablog::blog.archives')))
            ->dontSee(html_entity_decode(trans('larablog::blog.addpost')))
            ->see($blog->title);

        $this->visit('/blog/' . $blog->slug)
            ->assertResponseOk()
            ->see($blog->title)
            ->dontSee(html_entity_decode(trans('larablog::blog.editpost')));

        // check that people can't see pages they shouldn't be able to
        $response = $this->call('GET', '/blog/create');
        $this->assertEquals(404, $response->status());

        $response = $this->call('GET', '/blog/' . $blog->id . '/edit');
        $this->assertEquals(404, $response->status());

    }

    public function testPagePermissions(){

        // CHeck if pages are OK with a user
        $user = $this->createTestUser(0);
        $blog = $this->insertSampleData();

        $this->actingAs($user)
            ->visit('/blog')
            ->assertResponseOk()
            ->see(html_entity_decode(trans('larablog::blog.archives')))
            ->dontSee(html_entity_decode(trans('larablog::blog.addpost')))
            ->see($blog->title);

        $this->actingAs($user)
            ->visit('/blog/' . $blog->slug)
            ->assertResponseOk()
            ->see($blog->title);

        // Check if pages are OK with admin
        $user->type = 1;

        $this->actingAs($user)
            ->visit('/blog')
            ->see(html_entity_decode(trans('larablog::blog.archives')))
            ->see(html_entity_decode(trans('larablog::blog.addpost')));

        $this->actingAs($user)
            ->assertResponseOk()
            ->visit('/blog/' . $blog->slug)
            ->see($blog->title);

        $this->visit('/blog/create')
            ->assertResponseOk();

        $this->visit('/blog/' . $blog->id . '/edit')
            ->assertResponseOk();

        $user->destroy($user->id);
    }

    public function testModel(){
        // add an article
        $blog = $this->insertSampleData();

        // make sure it is in the database
        $this->seeInDatabase('blogs', [
            'title'     => $blog->title,
        ]);

        // update the article
        $data = $this->generateTestData();
        $blog->update($data);

        // make sure it is in the database
        $this->seeInDatabase('blogs', [
            'title'     => $data['title'],
        ]);

        // destroy if
        $blog->destroy($blog->id);

        // make sure it is NOT in the database
        $this->notSeeInDatabase('blogs', [
            'title'     => $data['title'],
        ]);

    }

    public function testAddBlog()
    {
        // turn off caching
        config(['blog.cache' => false]);

        // create an admin user
        $user = $this->createTestUser(1);

        // get id of tag for test
        $test = Tag::where('name', 'test')->first();
        // if we don't have one add it
        if (!count($test)) {
            Tag::create([
                'name' => 'test',
            ]);
        }

        // get some fake data
        $data = $this->generateTestData();

        // make a new blog, check that it is made, edit it, check that it is edited, change the date
        $this->actingAs($user)
            ->visit('/blog/create')
            ->assertResponseOk()
            ->see(html_entity_decode(trans('larablog::blog.writeanewarticle')))
            ->type($data['title'], 'title')
            ->type($data['slug'], 'slug')
            ->type($data['body'], 'body')
            ->select($test->id, 'tags')
            ->press(html_entity_decode(trans('larablog::blog.addpost')))
            ->see('Your blog has been created')
            ->seePageIs('/blog')
            ->see($data['title'])
            ->see(html_entity_decode(trans('larablog::blog.nocomments')));

        // check that blog page appears
        $this->actingAs($user)
            ->visit('/blog/' . $data['slug'])
            ->assertResponseOk()
            ->see($data['title'])
            ->see(html_entity_decode(trans('larablog::blog.editpost')));

        // make sure it appears in the RSS feed
        $this->visit('/feed')
            ->see($data['title']);
    }

    public function testEditBlog(){
        // create a user
        $user = $this->createTestUser(1);

        // create a blog
        $blog = $this->insertSampleData();

        // turn off caching
        config(['blog.cache' => false]);

        // check that the page is fine
        $this->actingAs($user)
            ->visit('/blog/' . $blog->slug)
            ->assertResponseOk()
            ->see($blog->title)
            ->see(html_entity_decode(trans('larablog::blog.editpost')));

        // make some more test data
        $data = $this->generateTestData();

        // go to edit page
        $this->actingAs($user)
            ->visit('/blog/' . $blog->id . '/edit')
            ->type($data['title'], 'title')
            ->type($data['slug'], 'slug')
            ->type($data['body'], 'body')
            ->press(html_entity_decode(trans('larablog::blog.update')))
            ->assertResponseOk();

        // make sure the new data is in the DB
        $this->seeInDatabase('blogs', [
            'title'     => $data['title'],
            'slug'      => $data['slug'],
        ]);

        // check that it appears in archives and on blog page
        $this->visit('/blog')
            ->see($data['title']);

        // set the blog to non-published
        $blog->published = 0;
        $blog->save();

        $user->type = 0;

        // check that is does NOT appear
        $this->actingAs($user)
            ->visit('/blog')
            ->dontSee($data['title']);

        // make sure it doesn't appear in the RSS feed
        $this->visit('/feed')
            ->dontSee($data['title']);

        // set the blog back to published
        $blog->published = 1;
        $blog->save();

        // check that it does appear
        $this->actingAs($user)
            ->visit('/blog')
            ->see($data['title']);

        // check that label is working
        $this->actingAs($user)
            ->visit('/blog/labels/test')
            ->see($data['title']);

        // change the date
        $blog->published_at = date('2020-01-01');
        $blog->save();

        // check that it doesn't appear as normal user
        $this->actingAs($user)
            ->visit('/blog')
            ->dontSee($data['title']);

        // make sure it doesn't appear in the RSS feed
        $this->visit('/feed')
            ->dontSee($data['title']);

        // check that it appears as admin
        $user->type = 1;

        $this->visit('/blog')
            ->see($data['title']);

        // change back to admin user, reset date on test article
        $user->type = 1;
        $user->save();

        $this->actingAs($user)
            ->visit('/blog/' . $data['slug'])
            ->click(html_entity_decode(trans('larablog::blog.editpost')))
            ->type('12/31/2015', 'published_at')
            ->press(html_entity_decode(trans('larablog::blog.update')))
            ->see('Your blog post has been edited!')
            ->see($data['title']);

        // flush the cache
        Cache::flush();
    }

    public function testCommentsModel(){
        $blog = $this->insertSampleData();
        $comment = $this->generateTestData();
        $body = $comment['title'];
        $user = $this->createTestUser(0);

        $comment = BlogComment::create([
            'blog_id'   => $blog->id,
            'user_id'   => $user->id,
            'body'      => $body,
        ]);

        // check that the comment is in the DB
        $this->seeInDatabase('blogcomments', [
            'body'      => $body,
            'blog_id'   => $blog->id,
        ]);

        // check that it appears on blog page
        $this->actingAs($user)
            ->visit('/blog/' . $blog->slug)
            ->see($body);
    }

    public function testLeavingComments(){
        $user = $this->createTestUser(0);
        $blog = $this->insertSampleData();

        $this->actingAs($user)
            ->visit('/blog/'. $blog->slug)
            ->assertResponseOk()
            ->see(html_entity_decode(trans('larablog::blog.leaveacomment')));

        // leave a comment
        $this->actingAs($user)
            ->visit('/blog/'. $blog->slug)
            ->type('Sample comment', 'body')
            ->press(html_entity_decode(trans('larablog::blog.postcomment')))
            ->see(html_entity_decode(trans('larablog::blog.commentposted')))
            ->see('Sample comment');

        // check that the comment is in the DB
        $this->seeInDatabase('blogcomments', [
            'body'      => 'Sample comment',
        ]);
    }

    private function createTestUser($admin = 0){
        $user = factory(App\User::class)->create();
        $user->type = $admin;
        $user->save();

        return $user;
    }

    private function generateTestData(){
        $faker = Faker\Factory::create();

        $text = $faker->paragraphs(3, true);
        $title = $faker->sentence;
        $slug = str_slug($title);
        $published_at = date('Y-m-d');

        $data = [
            'title' => $title,
            'slug'  => $slug,
            'body'  => $text,
            'published' => 1,
            'published_at'  => $published_at,
            'user_id'   => $this->createTestUser()->id,
        ];

        return $data;
    }

    private function insertSampleData(){
        return Blog::create($this->generateTestData());
    }
}

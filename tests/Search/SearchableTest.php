<?php

namespace BristolSU\Support\Tests\Search;

use BristolSU\Support\Search\Searchable;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SearchableTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->default('A blog title');
            $table->text('content')->default('This is some content');
            $table->timestamps();
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('comment')->default('A comment');
            $table->unsignedBigInteger('blog_post_id');
            $table->timestamps();
        });
    }

    public function newBlogPost(array $attributes = [], int $count = 1)
    {
        if($count === 1) {
            return BlogPost::create($attributes);
        }
        $blogs = [];
        for($i = 0, $i < $count; $i++;) {
            $blogs[] = BlogPost::create($attributes);
        }
        return $blogs;
    }

    public function newComment(array $attributes = [], int $count = 1)
    {
        if($count === 1) {
            return Comment::create($attributes);
        }
        $blogs = [];
        for($i = 0, $i < $count; $i++;) {
            $blogs[] = Comment::create($attributes);
        }
        return $blogs;
    }

    /** @test */
    public function a_model_can_be_searched(){
        BlogPost::$searchableArrayColumns = [];
        BlogPost::$searchableCommentColumns = [];
        $model1 = $this->newBlogPost(['title' => 'A blog title two']);
        $model2 = $this->newBlogPost(['content' => 'This is some content two']);
        $this->newBlogPost([], 10);

        $models = BlogPost::search('two')->get();
        $this->assertCount(2, $models);
        $this->assertContainsOnlyInstancesOf(BlogPost::class, $models);
        $this->assertModelEquals($model1, $models[0]);
        $this->assertModelEquals($model2, $models[1]);
    }

    /** @test */
    public function the_model_can_override_the_attributes_to_search_by(){
        BlogPost::$searchableArrayColumns = ['id', 'title'];
        $model1 = $this->newBlogPost(['title' => 'A blog title two']);
        $model2 = $this->newBlogPost(['content' => 'This is some content two']);
        $this->newBlogPost([], 10);

        $models = BlogPost::search('two')->get();
        $this->assertCount(1, $models);
        $this->assertContainsOnlyInstancesOf(BlogPost::class, $models);
        $this->assertModelEquals($model1, $models[0]);
    }

    /** @test */
    public function the_model_can_make_use_of_relationship_searching(){
        BlogPost::$searchableCommentColumns = ['comment'];

        $model1 = $this->newBlogPost(['title' => 'A blog title two']);
        $model2 = $this->newBlogPost(['content' => 'This is some content two']);;

        $comment1 = $this->newComment(['blog_post_id' => $model1->id, 'comment' => 'A comment three']);
        $comment2 = $this->newComment(['blog_post_id' => $model1->id, 'comment' => 'A comment four']);
        $comment3 = $this->newComment(['blog_post_id' => $model2->id, 'comment' => 'A comment three']);
        $comment4 = $this->newComment(['blog_post_id' => $model2->id, 'comment' => 'A comment six']);

        BlogPost::$test = true;
        BlogPost::all()->searchable();

        $models = BlogPost::search('four')->get();
        $this->assertCount(1, $models);
        $this->assertContainsOnlyInstancesOf(BlogPost::class, $models);
        $this->assertModelEquals($model1, $models[0]);

        $models = BlogPost::search('three')->get();
        $this->assertCount(2, $models);
        $this->assertContainsOnlyInstancesOf(BlogPost::class, $models);
        $this->assertModelEquals($model1, $models[0]);
        $this->assertModelEquals($model2, $models[1]);
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('blog_posts');
        parent::tearDown();
    }

}

class BlogPost extends Model
{
    use Searchable;

    public static $searchableArrayColumns = [];
public static $test = false;
    public static $searchableCommentColumns = [];

    protected $fillable = [
        'title', 'content'
    ];

    public function toSearchableArray()
    {
        $baseAttributes = [];
        if(static::$searchableArrayColumns === []) {
            $baseAttributes = $this->toArray();
        } else {
            $baseAttributes = $this->only(static::$searchableArrayColumns);
        }
        $commentAttributes = [];
        if(static::$searchableCommentColumns === []) {
            $commentAttributes = $this->comments->toArray();
        } else {
            $commentAttributes = $this->comments->map(fn($comment) => $comment->only(static::$searchableCommentColumns));
        }
        if($commentAttributes !== []) {
            $baseAttributes['comments'] = json_encode($commentAttributes);
        }
        return $baseAttributes;
    }

    protected function makeAllSearchableUsing($query)
    {
        return $query->with('comments');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

}

class Comment extends Model
{
    protected $fillable = [
        'comment', 'blog_post_id'
    ];

    public function blogPost()
    {
        return $this->belongsTo(BlogPost::class);
    }

}

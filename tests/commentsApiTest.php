<?php

use App\Post;
use App\Comment;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Request;



class commentsApiTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');
    }

    public function testGetComment()
    {
        $post=factory(Post::class)->create();
        $comments1=factory(Comment::class)->create(['commenta_type'=>"POST",'commenta_id'=>$post->id]);
        $comments2=factory(Comment::class)->create(['commenta_type'=>"POST",'commenta_id'=>$post->id]);
        $comments3=factory(Comment::class)->create(['commenta_type'=>"POST",'commenta_id'=>$post->id,'reply'=>$comments2->id]);
        
        $response=$this->call('GET','/comments',['type'=>'POST','id'=>$post->id]);
        $comments=Json_decode($response->getContent());
        $this->assertEquals(200,$response->getStatusCode(),$response->getContent());
        $this->assertEquals(2,count($comments));
        $this->assertSame(0,$comments[0]->reply);
        $this->assertSame($comments2->id,$comments[0]->id);
        // $this->assertObjectNotHasAttribute('email',$comments[0]);
        $this->assertSame(1,count($comments[0]->replies));
        $this->assertSame($comments1->id,$comments[1]->id);
        
    }

    public function testFieldsJson()
    {
        $post=factory(Post::class)->create();
        $comments2=factory(Comment::class)->create(['commenta_type'=>"POST",'commenta_id'=>$post->id]);
        $reply=factory(Comment::class)->create(['commenta_type'=>"POST",'commenta_id'=>$post->id,'reply'=>$comments2->id]);
        $response=$this->call('GET','/comments',['type'=>'POST','id'=>$post->id]);
        $comments=Json_decode($response->getContent());
        $this->assertObjectNotHasAttribute('email',$comments[0]);
        $this->assertObjectNotHasAttribute('ip',$comments[0]);
        $this->assertObjectHasAttribute('email_md5',$comments[0]);
        $this->assertObjectHasAttribute('ip_md5',$comments[0]);
        $this->assertEquals(md5($comments2->ip),$comments[0]->ip_md5);

        $this->assertObjectNotHasAttribute('email',$comments[0]->replies[0]);
        $this->assertObjectNotHasAttribute('ip',$comments[0]->replies[0]);
    }

    public function testPostComment()
    {
        $post=factory(Post::class)->create();
        $comment=factory(Comment::class)->make(['commenta_id'=>$post->id,'commenta_type'=>'post']);
        $response=$this->call('POST','/comments',$comment->getAttributes());
        $response_comment=json_decode($response->getContent());
        $this->assertEquals(200,$response->getStatusCode(),$response->getContent());
        $this->assertEquals(1,Comment::count());
        $this->assertEquals(md5(Request::ip()),$response_comment->ip_md5);
    }

    public function testPostCommentOnFakeContent()
    {
       $comment=factory(Comment::class)->make(['commenta_id'=>17,'commenta_type'=>'post']); 
       $response=$this->call('POST','/comments',$comment->getAttributes());
       $this->assertEquals(422,$response->getStatusCode());
       $this->assertEquals(0,Comment::count());
    }

    public function testPostCommentWithFakeEmail()
    {
        $post=factory(Post::class)->create();
        $comment=factory(Comment::class)->make(['commenta_id'=>$post->id,'commenta_type'=>'post','email'=>'fakee@dal.fr']);
        $response=$this->call('POST','/comments',$comment->getAttributes());
        $json=json_decode($response->getContent());
        
        $this->assertEquals(422,$response->getStatusCode(),$response->getContent());
        $this->assertEquals(0,Comment::count());
        $this->assertObjectHasAttribute('email',$json);
    }
}
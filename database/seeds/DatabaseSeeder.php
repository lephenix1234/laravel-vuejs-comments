<?php




use App\Post;
use App\Comment;
use Faker\Generator;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private $faker;

    public function __construct(Generator $faker)
    {
        return $this->faker=$faker;
    }
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
            // $post=Post::create([
            //     'name'=>$this->faker->name
            // ]);
            Comment::create(
            [
            'username'=>$this->faker->userName,
            'content'=>$this->faker->sentence,
            'email'=>$this->faker->email,
            'ip'=>$this->faker->Ipv4,
            'commenta_id'=>isset($post->id) ? $post->id : 1,
            'commenta_type'=>'post'
            ]
        );
    }


}

<?php

use App\Comment;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Comment::create(
            [
            'username'=>$this->faker->userName,
            'content'=>$this->faker->sentence,
            'email'=>$this->faker->email,
            'ip'=>$this->faker->Ipv4
            ]
        ,50);
    }
}

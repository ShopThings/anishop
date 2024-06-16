<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogComment;
use Illuminate\Database\Seeder;

class BlogCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blogIds = Blog::all();
        $blogs = $blogIds->random(mt_rand(10, 15));

        $blogs->each(function ($blog) {
            $comments = BlogComment::factory()->count(8)->forBlog($blog)->create();
            $comments->each(function (BlogComment $comment) use ($blog) {
                $count = mt_rand(0, 6);
                if (0 !== $count) {
                    BlogComment::factory()->count($count)->forBlog($blog)->forComment($comment)->create();
                }
            });
        });
    }
}

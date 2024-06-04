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
        $blogs = $blogIds->random(mt_rand(9, 14));

        $blogs->each(function ($blog) {
            $comments = BlogComment::factory()->count(15)->forBlog($blog)->create();
            $comments->each(function (BlogComment $comment) {
                $count = mt_rand(0, 8);
                if (0 !== $count) {
                    BlogComment::factory()->count($count)->forComment($comment)->create();
                }
            });
        });
    }
}

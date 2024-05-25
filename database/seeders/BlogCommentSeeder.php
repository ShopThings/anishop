<?php

namespace Database\Seeders;

use App\Models\BlogComment;
use Illuminate\Database\Seeder;

class BlogCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $comments = BlogComment::factory()->count(15)->create();
        $comments->each(function (BlogComment $comment) {
            $count = mt_rand(0, 8);
            if (0 !== $count) {
                BlogComment::factory()->count($count)->forComment($comment)->create();
            }
        });
    }
}

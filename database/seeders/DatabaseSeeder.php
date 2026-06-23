<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $tags = Tag::factory(10)->create();

        Project::factory(5)->create()->each(function ($project) use ($tags) {
            $issues = Issue::factory(6)->create(['project_id' => $project->id]);

            $issues->each(function ($issue) use ($tags) {
                $issue->tags()->attach(
                    $tags->random(rand(1, 3))->pluck('id')->toArray()
                );

                Comment::factory(rand(3, 5))->create(['issue_id' => $issue->id]);
            });
        });
    }
}

<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
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
        $users = User::factory(5)->create();

        $tags = Tag::factory(10)->create();

        Project::factory(5)->create()->each(function ($project) use ($tags, $users) {
            $project->update(['user_id' => $users->random()->id]);

            $issues = Issue::factory(6)->create(['project_id' => $project->id]);

            $issues->each(function ($issue) use ($tags, $users) {
                $issue->tags()->attach(
                    $tags->random(rand(1, 3))->pluck('id')->toArray()
                );

                $issue->members()->attach(
                    $users->random(rand(1, 2))->pluck('id')->toArray()
                );

                Comment::factory(rand(3, 5))->create(['issue_id' => $issue->id]);
            });
        });
    }
}

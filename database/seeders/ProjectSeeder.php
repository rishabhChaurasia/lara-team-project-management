<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User; // Assuming projects are associated with users
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->count() > 0) {
            foreach ($users as $user) {
                // Create projects for each user
                Project::create([
                    'name' => $user->name . '\'s Project',
                    'description' => 'Project created by ' . $user->name,
                    'status' => 'active',
                    'start_date' => now(),
                    'end_date' => now()->addMonths(3),
                    'created_by' => $user->id,
                ]);

                Project::create([
                    'name' => $user->name . '\'s Second Project',
                    'description' => 'Another project created by ' . $user->name,
                    'status' => 'active', // Changed from 'planning' to 'active' - valid enum value
                    'start_date' => now(),
                    'end_date' => now()->addMonths(2),
                    'created_by' => $user->id,
                ]);
            }
        }

        // Or create projects using factory if available
        // Project::factory()->count(10)->create();
    }
}
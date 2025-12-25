<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User; // Assuming teams are associated with users
use App\Models\Project; // Teams are associated with projects
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing users to associate with teams
        $users = User::all();

        $projects = Project::all();

        if ($projects->count() > 0) {
            foreach ($projects as $project) {
                // Create teams for each project
                Team::create([
                    'name' => $project->name . ' Team',
                    'description' => 'Team for the ' . $project->name . ' project',
                    'project_id' => $project->id,
                ]);
            }
        } else {
            // If no projects exist, create a team without a project relationship
            // Note: This will fail because project_id is required, so we'll need projects first
            // This approach ensures the dependency chain is respected
            echo "No projects found. Please run ProjectSeeder first.\n";
        }

        // Or create teams without specific associations
        /*
        Team::create([
            'name' => 'Development Team',
            'description' => 'Software development team',
            'created_by' => 1, // Assuming user with ID 1 exists
        ]);
        */
    }
}

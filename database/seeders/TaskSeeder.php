<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User; // Assuming tasks are assigned to users
use App\Models\Project; // Assuming tasks belong to projects
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing users and projects to associate with tasks
        $users = User::all();
        $projects = Project::all();

        if ($users->count() > 0 && $projects->count() > 0) {
            foreach ($projects as $project) {
                foreach ($users as $user) {
                    // Create tasks for each user in each project
                    Task::create([
                        'title' => 'Sample Task for ' . $project->name,
                        'description' => 'This is a sample task for the ' . $project->name . ' project assigned to ' . $user->name,
                        'status' => 'todo', // Options: todo, in_progress, review, completed
                        'priority' => 'medium', // Options: low, medium, high
                        'assigned_to' => $user->id,
                        'project_id' => $project->id,
                        'created_by' => $user->id, // Added required field
                        'due_date' => now()->addDays(rand(1, 30)),
                    ]);

                    // Create another task with different status
                    Task::create([
                        'title' => 'Another Task for ' . $project->name,
                        'description' => 'Another sample task for the ' . $project->name . ' project',
                        'status' => 'in_progress',
                        'priority' => 'high',
                        'assigned_to' => $user->id,
                        'project_id' => $project->id,
                        'created_by' => $user->id, // Added required field
                        'due_date' => now()->addDays(rand(1, 15)),
                    ]);
                }
            }
        }

        // Or create tasks without specific associations
        /*
        Task::create([
            'title' => 'Sample Task',
            'description' => 'This is a sample task for testing',
            'status' => 'todo',
            'priority' => 'medium',
            'assigned_to' => 1, // Assuming user with ID 1 exists
            'project_id' => 1, // Assuming project with ID 1 exists
            'created_by' => 1, // Required field
        ]);
        */
    }
}

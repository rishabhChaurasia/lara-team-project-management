# Laravel Team Project Management

A comprehensive team project management system built with Laravel, featuring personal workspaces for each user, role-based access control, and collaborative project management tools.

## Features

- **Personal Workspaces**: Each user automatically gets their own workspace upon registration
- **Role-Based Access Control**: Three distinct roles (Owner, Manager, Member) with different permissions
- **Project Management**: Create, manage, and organize projects
- **Task Management**: Assign tasks with statuses, priorities, and due dates
- **Team Management**: Create and manage teams within projects
- **Invitation System**: Invite users to join projects with specific roles
- **Time Tracking**: Track time spent on tasks
- **Task Comments**: Collaborate through task comments
- **Authentication & Authorization**: Built-in user authentication with role-based permissions

## Database Schema

### Core Tables
- `users` - User accounts and authentication
- `projects` - Project workspaces with status, dates, and creator
- `tasks` - Tasks with status, priority, assignees, and due dates
- `teams` - Teams within projects
- `project_members` - Junction table linking users to projects with roles
- `team_members` - Junction table linking users to teams
- `task_comments` - Comments on tasks
- `time_logs` - Time tracking for tasks
- `invitations` - Project invitations with tokens and expiration

### Role System
- `role` field in `project_members` table with values: 'owner', 'manager', 'member'
  - **Owner**: Full control, cannot be removed, can delete workspace
  - **Manager**: Can manage tasks/teams, cannot delete workspace
  - **Member**: Can view workspace, update assigned tasks only

## Installation

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm
- Database (MySQL, PostgreSQL, or SQLite)

### Setup Instructions

1. Clone the repository:
```bash
git clone <repository-url>
cd lara-team-project-management
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node.js dependencies:
```bash
npm install
```

4. Create environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your database settings in `.env` file

7. Run database migrations:
```bash
php artisan migrate
```

8. Run database seeders to populate initial data:
```bash
php artisan db:seed
```

9. Build frontend assets:
```bash
npm run build
```

### Quick Setup
Alternatively, you can use the provided setup script:
```bash
composer run setup
```

## Usage

### Registration & Workspace Creation
1. User registers → System auto-creates their personal workspace (project)
2. User becomes **owner** of their workspace automatically
3. User can create projects, tasks, and teams in their workspace

### Invitation Flow
1. Workspace owner invites other users via email
2. Invited user joins as **member** (default role)
3. Owner can change invited user's role to **manager** or keep as **member**

### Role Permissions
- **Owner**: Full control over the project (can delete, manage all aspects)
- **Manager**: Can manage tasks and teams (create, update, assign)
- **Member**: Can view workspace and update assigned tasks only

### Real-World Example
```
User: John registers
└─ Auto-creates "John's Workspace"
   └─ John's role = 'owner' (automatic)
   └─ John invites Sarah → Sarah joins as 'member'
   └─ John changes Sarah to 'manager'

User: Sarah also has her workspace
└─ Auto-creates "Sarah's Workspace"
   └─ Sarah's role = 'owner' (automatic)
   └─ Sarah invites Mike → Mike joins as 'member'

User: Mike has his workspace too
└─ Auto-creates "Mike's Workspace"
   └─ Mike's role = 'owner' (automatic)
```

## Development

### Running the Application
```bash
# Start the development server
php artisan serve

# Watch for frontend asset changes
npm run dev
```

### Running Tests
```bash
php artisan test
```

### Development Scripts
- `composer run dev` - Start development environment with concurrent processes
- `composer run test` - Run tests with configuration clearing

## Architecture

### Models
- `User` - Core user model with authentication and relationships
- `Project` - Project workspace model with creator and members
- `Task` - Task model with assignee, creator, status, and priority
- `Team` - Team model within projects
- `Invitation` - Invitation model for project invitations

### Controllers
- `ProjectController` - Handle project operations
- `TaskController` - Handle task operations
- `TeamController` - Handle team operations
- `TaskCommentController` - Handle task comments
- `TimeLogController` - Handle time tracking

### Policies
Role-based authorization policies ensure proper permissions:
- Project access based on user's role
- Task operations limited by role and ownership
- Team management restricted to appropriate roles

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Commit your changes (`git commit -m 'Add some amazing feature'`)
5. Push to the branch (`git push origin feature/amazing-feature`)
6. Open a Pull Request

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## Support

If you encounter any issues, please open an issue in the repository.
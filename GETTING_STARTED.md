# Getting Started - Team Project Management System

## Your Current Status
✅ Laravel installed  
✅ Database configured (MySQL: lara_team_project_management)  
❌ Authentication not setup  
❌ Views/Blade templates not created  

---

## Step-by-Step Guide for Beginners

### Step 1: Install Laravel Breeze (Authentication)
Laravel Breeze provides simple authentication scaffolding:

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install
npm run dev
```

**What this does:**
- Creates login, register, password reset pages
- Sets up authentication routes
- Adds Tailwind CSS for styling
- Creates basic layout templates

---

### Step 2: Check Database Connection
Make sure your MySQL is running and database exists:

```bash
php artisan migrate
```

**Expected output:** "Migration table created successfully"

**If error:** Make sure MySQL is running and database `lara_team_project_management` exists.

---

### Step 3: Run Initial Migrations

First, run the migrations that Breeze created:

```bash
php artisan migrate
```

**Expected output:** "Migration table created successfully"

---

### Step 4: Create Your First Migration (Users Table Update)
Add `is_admin` field to users table:

```bash
php artisan make:migration add_is_admin_to_users_table --table=users
```

**What this does:** Creates a new migration file in `database/migrations/`

**Next:** Open the created file and add this code in the `up()` method:

```php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->boolean('is_admin')->default(false)->after('email');
        $table->softDeletes();
    });
}
```

And in the `down()` method:

```php
public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('is_admin');
        $table->dropSoftDeletes();
    });
}
```

---

### Step 5: Create Projects Table Migration

```bash
php artisan make:migration create_projects_table
```

**Open the file and add:**

```php
public function up(): void
{
    Schema::create('projects', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description')->nullable();
        $table->enum('status', ['active', 'completed', 'archived'])->default('active');
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
        $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
        $table->timestamps();
        $table->softDeletes();
    });
}

public function down(): void
{
    Schema::dropIfExists('projects');
}
```

---

### Step 6: Create All Other Migrations

Run these commands one by one:

```bash
php artisan make:migration create_teams_table
php artisan make:migration create_project_members_table
php artisan make:migration create_team_members_table
php artisan make:migration create_tasks_table
php artisan make:migration create_task_comments_table
php artisan make:migration create_time_logs_table
```

**Don't worry!** I'll help you fill each migration file with the correct code.

---

### Step 7: Run All Migrations

After filling all migration files:

```bash
php artisan migrate
```

**What this does:** Creates all tables in your database

---

### Step 8: Create Models

```bash
php artisan make:model Project
php artisan make:model Team
php artisan make:model ProjectMember
php artisan make:model TeamMember
php artisan make:model Task
php artisan make:model TaskComment
php artisan make:model TimeLog
```

**What this does:** Creates model files in `app/Models/` folder

---

### Step 9: Create Your First Controller (Dashboard)

```bash
php artisan make:controller DashboardController
```

**What this does:** Creates `app/Http/Controllers/DashboardController.php`

**Note:** Authentication controllers are already created by Breeze

---

### Step 10: Test Your Setup

Start the development server:

```bash
php artisan serve
```

**Expected output:** "Server started on http://127.0.0.1:8000"

Visit: http://127.0.0.1:8000 in your browser

---

## Understanding Key Concepts

### What is a Migration?
Think of it as a blueprint for your database tables. It tells Laravel:
- What columns to create
- What type of data each column holds
- Relationships between tables

### What is a Model?
A model represents a database table in your code. It lets you:
- Read data from the table
- Insert new records
- Update existing records
- Delete records

### What is a Controller?
A controller handles requests from users. It:
- Receives the request
- Processes it (using models)
- Returns a response (HTML views or redirects)

### What is Laravel Breeze?
Breeze is Laravel's starter kit for authentication. It provides:
- Pre-built login and registration pages
- Password reset functionality
- Session-based authentication
- Beautiful Tailwind CSS styling

---

## Next Steps

After completing Step 9, you'll have:
- ✅ Database tables created
- ✅ Models ready
- ✅ Basic structure in place

**Then we'll build:**
1. Authentication (Register, Login, Logout)
2. Projects CRUD
3. Tasks CRUD
4. Teams management
5. And more...

---

## Common Errors & Solutions

### Error: "Access denied for user 'root'@'localhost'"
**Solution:** Check your `.env` file, make sure DB_PASSWORD is correct

### Error: "Base table or view not found"
**Solution:** Run `php artisan migrate`

### Error: "npm: command not found"
**Solution:** Install Node.js from https://nodejs.org

### Error: "Class not found"
**Solution:** Run `composer dump-autoload`

### Error: "Route not found"
**Solution:** Run `php artisan route:clear`

---

## Useful Commands

```bash
# Clear all caches
php artisan optimize:clear

# See all routes
php artisan route:list

# See all migrations status
php artisan migrate:status

# Rollback last migration
php artisan migrate:rollback

# Fresh start (delete all data and re-migrate)
php artisan migrate:fresh
```

---

## Ready to Start?

Run these commands to begin:

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install
npm run dev
```

In a separate terminal, run:
```bash
php artisan serve
```

Then visit http://127.0.0.1:8000 and you'll see login/register links!

Let me know when you're done, and I'll help you with the next step!

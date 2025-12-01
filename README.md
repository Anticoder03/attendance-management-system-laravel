# Laravel Attendance System

A comprehensive attendance management system built with Laravel 12 for educational institutions. This system allows administrators to manage teachers, subjects, students, and assignments, while teachers can take attendance and view detailed reports.

## Features

### Admin Features
- **Teacher Management**: Create, edit, and delete teachers
- **Subject Management**: Create, edit, and delete subjects
- **Student Management**: Create, edit, and delete students, enroll them in subjects
- **Assignment Management**: Assign teachers to subjects

### Teacher Features
- **Take Attendance**: Record attendance for students in assigned subjects with status options (Present, Absent, Late, Excused)
- **View Reports**:
  - **Individual Report**: View attendance for a specific student
  - **Grouped Report**: View attendance for all students with statistics (date-wise or month-wise filtering)
  - **Date-wise Report**: View attendance for a specific date
  - **Month-wise Report**: View calendar-style attendance for an entire month

## Requirements

- PHP 8.2 or higher
- Composer
- SQLite (included) or MySQL/PostgreSQL
- Node.js and NPM (for frontend assets)

## Installation

1. **Clone the repository** (if applicable) or navigate to the project directory

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

3. **Install Node dependencies**:
   ```bash
   npm install
   ```

4. **Set up environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database** (SQLite is configured by default):
   - The database file is located at `database/database.sqlite`
   - If using a different database, update `.env` accordingly

6. **Run migrations**:
   ```bash
   php artisan migrate
   ```

7. **Create the first admin user**:
   ```bash
   php artisan tinker
   ```
   Then in tinker:
   ```php
   App\Models\User::create([
       'name' => 'Admin',
       'email' => 'admin@example.com',
       'password' => bcrypt('password'),
       'role' => 'admin'
   ]);
   ```

8. **Build frontend assets**:
   ```bash
   npm run build
   ```

9. **Start the development server**:
   ```bash
   php artisan serve
   ```

10. **Access the application**:
    - Visit `http://localhost:8000`
    - Login with the admin credentials you created

## Usage

### For Administrators

1. **Create Teachers**:
   - Navigate to Teachers → Add Teacher
   - Fill in teacher details and create

2. **Create Subjects**:
   - Navigate to Subjects → Add Subject
   - Add subject name, code (optional), and description

3. **Create Students**:
   - Navigate to Students → Add Student
   - Fill in student details and select subjects they're enrolled in

4. **Assign Teachers to Subjects**:
   - Navigate to Assignments
   - Select a teacher and subject, then click "Assign"

### For Teachers

1. **Take Attendance**:
   - Go to Dashboard
   - Click "Take Attendance" for the desired subject
   - Select date and mark each student's status
   - Save attendance

2. **View Reports**:
   - Go to Reports
   - Select the report type and subject
   - Apply filters as needed (date or month)

## User Roles

- **Admin**: Can manage teachers, subjects, students, and assignments
- **Teacher**: Can take attendance and view reports for assigned subjects

## Database Structure

- **users**: Stores admin and teacher accounts
- **subjects**: Stores subject information
- **students**: Stores student information
- **teacher_subject**: Pivot table for teacher-subject assignments
- **student_subject**: Pivot table for student enrollment
- **attendances**: Stores attendance records

## Technologies Used

- Laravel 12
- PHP 8.2+
- Tailwind CSS 4
- SQLite/MySQL/PostgreSQL

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

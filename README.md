# Project Management System

A full-stack web application for managing projects and tasks with weighted progress calculation based on task difficulty.

## Tech Stack

- **Backend:** Laravel 10
- **Frontend:** React 18 with TypeScript and Vite
- **UI Library:** Shadcn/ui + Tailwind CSS
- **Database:** MySQL 8.0
- **Containerization:** Docker & Docker Compose

## Features

- **Project Management**
  - Create and list projects
  - View project details with progress visualization
  - Projects ordered by most recent first

- **Task Management**
  - Create tasks with difficulty levels (Low, Medium, High)
  - Mark tasks as completed/uncompleted
  - Delete tasks with confirmation
  - Real-time progress updates

- **User Interface**
  - Responsive design for mobile and desktop
  - Loading states with animated spinners
  - Error handling with user-friendly alerts
  - Form validation with character counters
  - Modern UI with smooth animations

- **Performance**
  - Optimized API queries with eager loading
  - Database indexes for faster queries
  - Efficient progress calculation

## Effort Points System

The project progress is calculated using a weighted system:
- **Low difficulty:** 1 effort point
- **Medium difficulty:** 4 effort points
- **High difficulty:** 12 effort points

Progress is calculated as the percentage of completed effort points relative to total effort points across all tasks in a project.

## Installation and Setup

### Prerequisites
- Docker
- Docker Compose
- Git

### Running the Application

1. Clone the repository:
```bash
git clone https://github.com/lukreitor/project-management-system.git
cd project-management-system
```

2. Start the Docker containers:
```bash
docker-compose up -d
```

3. Install backend dependencies:
```bash
docker-compose exec app composer install
```

4. Set up environment file:
```bash
cp backend/.env.example backend/.env
docker-compose exec app php artisan key:generate
```

5. Run database migrations:
```bash
docker-compose exec app php artisan migrate
```

6. (Optional) Seed the database with sample data:
```bash
docker-compose exec app php artisan db:seed --force
```

7. Install frontend dependencies:
```bash
docker-compose exec frontend npm install
```

8. Access the application:
- Frontend: http://localhost:3000
- Backend API: http://localhost:8000/api
- API Health Check: http://localhost:8000/api/health

## API Endpoints

### Projects
- `GET /api/projects` - List all projects with tasks and progress (ordered by newest first)
- `GET /api/projects/:id` - Get specific project details with tasks and weighted progress
- `POST /api/projects` - Create a new project
  - Required: `name` (string, 3-255 characters)

### Tasks
- `POST /api/tasks` - Create a new task
  - Required: `title` (string, 3-255 characters), `difficulty` (low/medium/high), `project_id` (integer)
- `PATCH /api/tasks/:id/toggle` - Toggle task completion status
- `DELETE /api/tasks/:id` - Delete a task

### Response Format
All API responses follow this structure:
```json
{
  "data": { /* resource data */ }
}
```

Errors return:
```json
{
  "message": "Error description",
  "errors": { /* validation errors */ }
}
```

## Running Tests

Run the full test suite:
```bash
docker-compose exec app php artisan test
```

Run specific test types:
```bash
# Unit tests only
docker-compose exec app php artisan test --testsuite=Unit

# Feature tests only
docker-compose exec app php artisan test --testsuite=Feature
```

Current test coverage: 21/25 tests passing (84%)

## Development

### Backend
- Laravel 10 with PHP 8.1+
- PSR-4 autoloading
- PHPUnit for testing
- Service layer pattern for business logic

### Frontend
- React 18 with TypeScript
- Vite for fast development and building
- Shadcn/ui components with Radix UI primitives
- Tailwind CSS for styling
- React Router for navigation
- Axios for API communication

### Database
- MySQL 8.0
- Migrations for schema management
- Seeders for sample data
- Indexed columns for optimized queries

## Project Structure

```
.
├── backend/                 # Laravel backend
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/ # API controllers
│   │   │   ├── Requests/    # Form request validation
│   │   │   └── Resources/   # API resources
│   │   ├── Models/          # Eloquent models
│   │   └── Services/        # Business logic services
│   ├── database/
│   │   ├── migrations/      # Database migrations
│   │   └── seeders/         # Database seeders
│   └── tests/               # PHPUnit tests
│
├── frontend/                # React frontend
│   └── src/
│       ├── components/      # React components
│       │   ├── ui/          # Base UI components
│       │   ├── projects/    # Project components
│       │   ├── tasks/       # Task components
│       │   └── layout/      # Layout components
│       ├── pages/           # Page components
│       ├── services/        # API service layer
│       └── types/           # TypeScript types
│
└── docker-compose.yml       # Docker configuration
```

## Troubleshooting

### Database Connection Issues
If you encounter database connection errors:
```bash
# Wait for MySQL to be fully ready
docker-compose exec db mysql -u root -p
# Then try migrations again
```

### Port Already in Use
If ports 3000, 8000, or 3306 are already in use:
1. Stop the conflicting services
2. Or modify ports in `docker-compose.yml`

### Frontend Build Issues
Clear cache and reinstall:
```bash
docker-compose exec frontend rm -rf node_modules package-lock.json
docker-compose exec frontend npm install
```

## License

MIT

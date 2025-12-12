# Project Management System

A full-stack web application for managing projects and tasks with weighted progress calculation based on task difficulty.

## Tech Stack

- **Backend:** Laravel (latest stable version)
- **Frontend:** React.js / Vue.js / Next.js
- **Database:** MySQL
- **Containerization:** Docker & Docker Compose

## Features

- Create and manage projects
- Create tasks with difficulty levels (Low, Medium, High)
- Mark tasks as completed/uncompleted
- Delete tasks
- Weighted progress calculation based on effort points
- Real-time progress updates

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

6. Install frontend dependencies:
```bash
docker-compose exec frontend npm install
```

7. Access the application:
- Frontend: http://localhost:3000
- Backend API: http://localhost:8000

## API Endpoints

### Projects
- `GET /api/projects` - List all projects
- `GET /api/projects/:id` - Get project details with progress
- `POST /api/projects` - Create a new project

### Tasks
- `POST /api/tasks` - Create a new task
- `PATCH /api/tasks/:id/toggle` - Toggle task completion status
- `DELETE /api/tasks/:id` - Delete a task

## Running Tests

```bash
docker-compose exec app php artisan test
```

## License

MIT

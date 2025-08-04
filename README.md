# Task Manager API

# Task Manager API (PHP + SQLite)

This project is a simple Task Management REST API built using **pure PHP** (no frameworks) with **SQLite** for database storage. It is fully Dockerized and follows an MVC-like structure.

---

## Features

- CRUD API for tasks
- SQLite database for persistence
- Input validation with proper error responses
- Filtering by status (`?status=pending`)
- Dockerized for easy setup

---

## Requirements

- PHP 8.1+ (if running locally)
- SQLite (auto-installed with PHP)
- Docker

---

## Installation and Setup

### 1. Clone the repository

```bash
git clone https://github.com/your-username/task-manager-api.git
cd task-manager-api
```

---

### 2. Run with Docker

1. Build and run the container:

   ```bash
   docker build -t task-manager-api .
   docker run -p 8000:80 task-manager-api
   ```

   Or use Docker Compose:

   ```bash
   docker-compose up --build
   ```

2. Access the API at:
   ```
   http://localhost:8000
   ```

---

## API Endpoints

# Task Manager API - Endpoints

## 1. Register User (POST)

**Request**

```bash
curl -X POST http://localhost:8000/auth/register   -H "Content-Type: application/json"   -d '{"username": "testuser", "password": "testpassword"}'
```

**Response**

```json
{
  "message": "User registered"
}
```

---

## 2. Login User (POST)

**Request**

```bash
curl -X POST http://localhost:8000/auth/login   -H "Content-Type: application/json"   -d '{"username": "testuser", "password": "testpassword"}'
```

**Response**

```json
{
  "token": "your_jwt_token_here"
}
```

---

## 3. Create Task (POST)

**Request**

```bash
curl -X POST http://localhost:8000/tasks   -H "Content-Type: application/json"   -H "Authorization: Bearer your_jwt_token_here"   -d '{"title": "Test Task", "description": "This is a sample task"}'
```

**Response**

```json
{
  "message": "Task created",
  "id": 1
}
```

---

## 4. Get All Tasks (GET)

**Request**

```bash
curl http://localhost:8000/tasks   -H "Authorization: Bearer your_jwt_token_here"
```

**With filtering**

```bash
curl http://localhost:8000/tasks?status=completed   -H "Authorization: Bearer your_jwt_token_here"
```

**Response**

```json
[
  {
    "id": 1,
    "title": "Test Task",
    "description": "This is a sample task",
    "status": "pending",
    "created_at": "2025-08-04 08:00:00",
    "updated_at": "2025-08-04 08:00:00"
  }
]
```

---

## 5. Get Task by ID (GET)

**Request**

```bash
curl http://localhost:8000/tasks/1   -H "Authorization: Bearer your_jwt_token_here"
```

**Response**

```json
{
  "id": 1,
  "title": "Test Task",
  "description": "This is a sample task",
  "status": "pending",
  "created_at": "2025-08-04 08:00:00",
  "updated_at": "2025-08-04 08:00:00"
}
```

---

## 6. Update Task (PUT)

**Request**

```bash
curl -X PUT http://localhost:8000/tasks/1   -H "Content-Type: application/json"   -H "Authorization: Bearer your_jwt_token_here"   -d '{"title": "Updated Task", "description": "Updated description", "status": "completed"}'
```

**Response**

```json
{
  "message": "Task updated"
}
```

---

## Error Responses

Examples:

```json
{
  "error": "Task not found"
}
```

```json
{
  "error": "Authorization header missing"
}
```

```json
{
  "error": "Invalid or expired token"
}
```

```json
{
  "error": "Username already exists"
}
```

---

## Authorization Rules

- **/auth/register** and **/auth/login** are public endpoints (no token required).
- **All /tasks endpoints require an Authorization header** with a valid JWT token:

```bash
-H "Authorization: Bearer your_jwt_token_here"
```

Examples:

```json
{
  "error": "Task not found"
}
```

```json
{
  "error": "Title and description are required"
}
```

---

## Database Schema

Each task has the following fields:

- `id` (auto-increment)
- `title` (string)
- `description` (text)
- `status` (enum: pending, in-progress, completed)
- `created_at` (timestamp)
- `updated_at` (timestamp)

---

---

## JWT Authentication

This API includes JWT-based authentication by:

1. Adding a `users` table with `username` and `password_hash`.
2. Implementing login and registration endpoints.
3. Generating and verifying JWT tokens for protected routes.

---

## Project Structure

```
task-manager-api/
├── src/
│   ├── Controllers/        # Handles request logic
│   ├── Database/           # Database connection and initialization
│   ├── Models/             # Database models (Task, User)
│   └── Routes/             # API route definitions
├── config/
│   └── apache.conf         # Apache virtual host configuration
├── Dockerfile              # Docker build file
├── docker-compose.yml      # Docker compose for services
├── index.php               # API entry point (front controller)
├── .env                    # Environment variables (JWT_SECRET)
├── .htaccess               # Apache rewrite rules for clean URLs
└── README.md               # Documentation
```

---

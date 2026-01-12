# api2spec Symfony Fixture

A minimal Symfony 7 API fixture for testing api2spec framework detection and route extraction.

## Requirements

- PHP 8.2+
- Composer

## Installation

```bash
composer install
```

## Running

### Local Development

```bash
php -S localhost:8000 -t public
```

### Docker

```bash
docker compose up
```

## API Endpoints

### Health
- `GET /health` - Health check
- `GET /health/ready` - Readiness check

### Users
- `GET /users` - List all users
- `POST /users` - Create a user
- `GET /users/{id}` - Get a user
- `PUT /users/{id}` - Update a user
- `DELETE /users/{id}` - Delete a user
- `GET /users/{userId}/posts` - Get user's posts

### Posts
- `GET /posts` - List all posts
- `GET /posts/{id}` - Get a post
- `POST /posts` - Create a post

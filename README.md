# Project Setup Guide

## 1. Introduction
This document provides a complete guide to install, configure, and run the project. It covers prerequisites, setup steps, common issues, and testing instructions.

---

## 2. Project Overview

- **Backend:** Symfony 6
- **Architecture:** Hexagonal Architecture with Domain-Driven Design (DDD)
- **Infrastructure:** Dockerized services with separate containers for backend, database, and message queue
- **Messaging:** Symfony Messenger with RabbitMQ for asynchronous command and event handling

### Project Structure

```
project-root/
│── backend/                 # Symfony backend source code
│   ├── src/                 # Application code (domain, application, infrastructure)
│   │   ├── Product/         # Product domain logic and use cases
│   │   ├── Shared/          # Shared utilities and common code
│   │   ├── Auth/            # Authentication and security
│   ├── config/              # Configuration files
│   ├── bin/                 # Symfony CLI and console commands
│   ├── tests/               # PHPUnit tests
│   ├── public/              # Web public directory (document root)
│   ├── .env                 # Environment variables for Symfony
│   └── composer.json        # PHP dependencies
│
│── docker/                  # Docker and Docker Compose configurations
│── Makefile                 # Make commands for automation
│── README.md                # This documentation file
```

---

## 3. Prerequisites

Make sure the following are installed on your development machine:

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Make](https://www.gnu.org/software/make/)
- [Git](https://git-scm.com/) (if cloning the repository)
- Proper permissions to run Docker containers

---

## 4. Installation & Setup

### 4.1 Clone the Repository
If starting from a ZIP archive, extract and enter the directory:

```bash
unzip project.zip -d project
cd project
```

### 4.2 Environment Configuration
Copy example environment files and adjust as needed:

```bash
cp backend/.env.example backend/.env
# If frontend exists, do the same for frontend/.env
```

Edit `.env` files to set database credentials, RabbitMQ connection details, API secrets, etc.

### 4.3 Start the Containers

Use Make to build and start the environment:

```bash
make up
```

This command builds the Docker images and launches all containers including backend, MySQL, RabbitMQ, and workers.

### 4.4 Run Database Migrations
Run database migrations to set up the schema:

```bash
make migrate
```
### 4.5 Verify Containers are Running

Check container status:

```bash
docker ps
```

Stream logs:

```bash
docker-compose logs -f
```

---

## 5. Common Issues & Troubleshooting

### 5.1 Permission Errors
If you get file permission issues:

```bash
sudo chown -R $USER:$USER .
chmod +x Makefile
sudo usermod -aG docker $USER
```

Then log out and back in.

### 5.2 Containers Fail to Start

Try cleaning up volumes and stale containers:

```bash
make down
docker system prune -af
make up
```

### 5.3 Application Not Responding

- Confirm containers are up (`docker ps`)
- Check logs for errors (`docker-compose logs -f`)
- Verify environment variables and ports

---

## 6. Stopping and Cleaning the Project

Stop running containers:

```bash
make down
```

Clean volumes and cache for a fresh start:

```bash
make clean
```

---

## 7. Running Tests and Code Quality

### 7.1 PHP_CodeSniffer (PHPCS)

Check coding standards compliance:

```bash
vendor/bin/phpcs --standard=PSR12 src/
vendor/bin/phpcbf --standard=PSR12 src/
```

Or with composer scripts:

```bash
composer phpcs
composer phpcbf
```

### 7.2 PHPUnit Tests

Make sure `APP_ENV=test` is set in `.env` for testing environment.

Run all tests:

```bash
vendor/bin/phpunit
```
or via make command:

```bash 
make test
```
Or via composer script:

```bash
composer test
```

---

## 8. API Documentation

Access API docs (Swagger/OpenAPI):

```
http://localhost:8001/api/doc
```

Modify API doc settings in:

```
config/packages/nelmio_api_doc.yaml
```

---

## 9. Makefile Commands

| Command           | Description                          |
|-------------------|------------------------------------|
| `make up`         | Build and start Docker containers  |
| `make down`       | Stop and remove containers & volumes|
| `make clean`      | Remove containers, volumes, cache  |
| `make migrate`    | Run Doctrine migrations             |
| `make test`       | Run PHPUnit tests                   |
| `make phpcs`      | Run PHP Code Sniffer               |
| `make logs`       | Tail logs of all containers         |
| `make worker-logs`| Tail logs of messenger worker       |

---

## 10. Conclusion

Following this guide will help you set up a stable development environment, run tests, and maintain code quality in a hexagonal architecture Symfony backend with asynchronous messaging powered by RabbitMQ.

For further assistance, consult official docs for Symfony, Docker, and RabbitMQ.

---


# Calendar Event API

This is a simplified API built with PHP Laravel framework to manage calendar events scheduling. Though simplified, it is designed to be easily built upon (extensible) to become a fully functional API for calendar events management. This API caters for multiple users and enables them to:

+ Add upcoming events 
+ View all upcoming events within a specified date range, and the weather forecast for the event locations.
+ View details of a specific event and the weather forecast for the event location.
+ Invite users to events. An invitation email will be sent.
+ Update events
+ Delete events
+ Get a list of locations where events are coming up within a specified date range

## Requirements
+ Laravel 8. +
+ PHP 7.3 +
+ MySQL 8.0 +
+ [Postman](https://www.postman.com/downloads/) 
+ Docker


## Installation

Installing this app is pretty straightforward. There are different ways you can run this app, but I recommend using Laravel sail, which is a light-weight command-line interface for interacting with Laravel's default Docker development environment. Laravel Sail is supported on macOS, Linux, and Windows. 

To get started:

+ Make sure you have [Composer](https://getcomposer.org/doc/00-intro.md) installed on your computer to manage your composer dependencies.
+ Download and install [Docker Desktop](https://www.docker.com/)  
+ Clone this repo
+ Cd in the application folder then [setup laravel sail](https://laravel.com/docs/8.x/sail) for the application. Once laravel is setup, you can run then run sail up command to start up your docker ccontainers:

```
cd calendar-event-api

composer require laravel/sail --dev

./vendor/bin/sail up
`````
Once the application's containers have been started, you may access the project in your web browser at: http://localhost.

The first time you run the Sail up command, Sail's application containers will be built on your machine. This could take several minutes. Don't worry, subsequent attempts to start Sail will be much faster.

__Database__

The application's docker-compose.yml file contains an entry for a MySQL container. This container uses a Docker volume so that the data stored in your database is persisted even when stopping and restarting your containers. In addition, when the MySQL container is starting, it will ensure a database exists whose name matches the value of your DB_DATABASE environment variable.

Once you have started your containers, you may connect to the MySQL instance within your application using a database client by setting your DB_HOST environment variable within your application's .env file to mysql.

After connecting to the database, run the applications database migrations to create the required database. With Laravel sail the command to run migrations should be

```
sail artisan migrate
`````
+ 


## Usage


__Endpoints__
-  Register new user

```
POST /api/register

Data:
{
	"username" : "testuser",
	"password" : "foobar",
	"first_name" : "test",
	"last_name" : "user",
	"email" : "test@domain.com",
}


Response:
{
    "success": true,
    "status_code": 201,
    "message": "User created",
    "data": {
        "username": "testuser",
        "first_name": "Test",
        "last_name": "User",
        "email": "test@domain.com",
        "updated_at": "2021-03-19T21:16:31.000000Z",
        "created_at": "2021-03-19T21:16:31.000000Z",
        "id": 21
    }
}
`````

-  User Login
```
POST /api/login

Data:
{
	"email" : "test@domain.com",
	"password" : "foobar",
}


Response:
{
    "success": true,
    "status_code": 200,
    "message": "Login successful",
    "data": {
        "token": "2|azCmFbALQRQKYYwemtgu0Qy0xU9epH3MZi5weYz1"
    }
}
`````

-  User Logout
```
GET /api/logout

Headers:
{
	Authorization: Bearer {token}
	Accept: application/json
}

Response:
{
    "success": true,
    "status_code": 200,
    "message": "Login successful",
    "data": {
        "token": "2|azCmFbALQRQKYYwemtgu0Qy0xU9epH3MZi5weYz1"
    }
}
`````




__Invitation Emails__


## 


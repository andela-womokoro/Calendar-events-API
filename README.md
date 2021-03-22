
# Calendar Event API

This is a simplified REST API built with PHP Laravel framework to manage calendar events scheduling. Though simplified, it is designed to be easily built upon (extensible) to become a fully functional API for calendar events management. This API caters for multiple users and enables them to:

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
+ Postman 
+ Docker


## Installation

Installing this app is pretty straightforward. There are different ways you can run this app, but I recommend using Laravel sail, which is a light-weight command-line interface for interacting with Laravel's default Docker development environment. Laravel Sail is supported on macOS, Linux, and Windows. 

To get started:

+ Make sure you have [Composer](https://getcomposer.org/doc/00-intro.md) installed on your computer to manage your composer dependencies.
+ Download and install [Docker Desktop](https://www.docker.com/)  
+ Clone this repo
+ Cd in the application folder then [setup laravel sail](https://laravel.com/docs/8.x/sail) for the application. Once laravel sail is setup, you can then run `sail up` command to start up your docker containers. The containers needed to run this application are Laravel and MySQL containers. They are bundled with laravel sail once it has been setup.

```
cd calendar-event-api

composer require laravel/sail --dev

./vendor/bin/sail up
`````
Once the application's containers have been started, you may access the project in your web browser at: http://localhost.

The first time you run the `sail up` command, Sail's application containers will be built on your machine. This could take several minutes. Don't worry, subsequent attempts to start Sail will be much faster.

__Database__

The application's `docker-compose.yml` file contains an entry for a MySQL container. This container uses a Docker volume so that the data stored in your database is persisted even when stopping and restarting your containers. In addition, when the MySQL container is starting, it will ensure a database exists whose name matches the value of your `DB_DATABASE` environment variable.

Once you have started your containers, you may connect to the MySQL instance within your application using a database client by setting your `DB_HOST` environment variable within your application's `.env` file to `mysql`.

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE={database name}
DB_USERNAME={username}
DB_PASSWORD={password}
`````

After connecting to the database, run the application's database migrations to create the required database. With Laravel sail the command to run migrations should be

```
sail artisan migrate
`````


## Usage


__Endpoints__

The API endpoints can be tested with [Postman](https://www.postman.com/downloads/) or similar tools.

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

-  Create an event
```
GET /api/events

Headers:
{
	Authorization: Bearer {token}
	Accept: application/json
}

Data:
{
	"description" : "Team meeting",
	"date" : "2021-03-30",
	"time" : "15:00:00",
	"location" : "Brussels",
}

Response:
{
    "success": true,
    "status_code": 201,
    "message": "Event created",
    "data": {
        "description": "Team meeting",
        "date": "2021-03-30",
        "time": "15:00:00",
        "location": "Brussels",
        "created_by": 21,
        "updated_at": "2021-03-19T21:29:49.000000Z",
        "created_at": "2021-03-19T21:29:49.000000Z",
        "id": 37
    }
}
`````

-  View single event
```
GET /api/events/37

Headers:
{
	Authorization: Bearer {token}
	Accept: application/json
}

Response:
{
    "success": true,
    "status_code": 200,
    "message": "Ok",
    "data": {
        "id": 37,
        "description": "Team meeting",
        "date": "2021-03-30",
        "time": "15:00:00",
        "location": "Brussels",
        "created_by": 21,
        "created_at": "2021-03-19T21:29:49.000000Z",
        "updated_at": "2021-03-19T21:29:49.000000Z",
        "weather": {
            "description": "few clouds",
            "temperature": 4.11,
            "humidity": 75
        }
    }
}
`````

-  View list of events
```
GET /api/events?from_date=2021-03-01&to_date=2021-03-30

Headers:
{
	Authorization: Bearer {token}
	Accept: application/json
}

Response:
{
    "success": true,
    "status_code": 200,
    "message": "Ok",
    "data": {
        "total": 5,
        "per_page": 10,
        "current_page": 1,
        "last_page": 1,
        "first_page_url": "http://localhost/api/events?from_date=2021-03-01&to_date=2021-03-30&page=1",
        "last_page_url": "http://localhost/api/events?from_date=2021-03-01&to_date=2021-03-30&page=1",
        "next_page_url": null,
        "prev_page_url": null,
        "from": 1,
        "to": 5,
        "payload": [
            {
                "id": 39,
                "description": "One on one",
                "date": "2021-03-03",
                "time": "11:00:00",
                "location": "Paris",
                "created_by": 21,
                "created_at": "2021-03-19T21:33:40.000000Z",
                "updated_at": "2021-03-19T21:33:40.000000Z",
                "weather": {
                    "description": "clear sky",
                    "temperature": 4.86,
                    "humidity": 56
                }
            },
            {
                "id": 41,
                "description": "Sprint planning",
                "date": "2021-03-09",
                "time": "10:00:00",
                "location": "Ghent",
                "created_by": 21,
                "created_at": "2021-03-19T21:34:39.000000Z",
                "updated_at": "2021-03-19T21:34:39.000000Z",
                "weather": {
                    "description": "scattered clouds",
                    "temperature": 3.17,
                    "humidity": 82
                }
            },
            {
                "id": 38,
                "description": "weekly meeting",
                "date": "2021-03-11",
                "time": "11:30:00",
                "location": "London",
                "created_by": 21,
                "created_at": "2021-03-19T21:33:09.000000Z",
                "updated_at": "2021-03-19T21:33:09.000000Z",
                "weather": {
                    "description": "clear sky",
                    "temperature": 5.3,
                    "humidity": 70
                }
            },
            {
                "id": 40,
                "description": "Company all-hands meeting",
                "date": "2021-03-25",
                "time": "11:00:00",
                "location": "London",
                "created_by": 21,
                "created_at": "2021-03-19T21:34:12.000000Z",
                "updated_at": "2021-03-19T21:34:12.000000Z",
                "weather": {
                    "description": "clear sky",
                    "temperature": 5.3,
                    "humidity": 70
                }
            },
            {
                "id": 37,
                "description": "Team meeting",
                "date": "2021-03-30",
                "time": "15:00:00",
                "location": "Brussels",
                "created_by": 21,
                "created_at": "2021-03-19T21:29:49.000000Z",
                "updated_at": "2021-03-19T21:29:49.000000Z",
                "weather": {
                    "description": "few clouds",
                    "temperature": 3.82,
                    "humidity": 81
                }
            }
        ]
    }
}
`````

-  Update an event
```
PUT /api/events/38

Headers:
{
	Authorization: Bearer {token}
	Accept: application/json
}

Data:
{
	"description" : "Sprint planning",
	"date" : "2021-03-09",
}

Response:
{
    "success": true,
    "status_code": 200,
    "message": "Ok",
    "data": {
        "id": 38,
        "description": "weekly meeting",
        "date": "2021-03-11",
        "time": "11:30:00",
        "location": "London",
        "created_by": 21,
        "created_at": "2021-03-19T21:33:09.000000Z",
        "updated_at": "2021-03-19T21:33:09.000000Z",
        "weather": {
            "description": "clear sky",
            "temperature": 13.92,
            "humidity": 50
        }
    }
}
`````

-  Delete an event
```
DELETE /api/events/37

Headers:
{
	Authorization: Bearer {token}
	Accept: application/json
}

Response:
{
    "success": true,
    "status_code": 200,
    "message": "Event deleted",
    "data": []
}
`````

-  Invite a user to an event
```
POST /api/events/41/invite

Data:
{
	user_id: 20
}

Headers:
{
	Authorization: Bearer {token}
	Accept: application/json
}

Response:
{
    "success": true,
    "status_code": 201,
    "message": "Invitation created",
    "data": {
        "id": 3,
        "email_sent": "yes",
        "event_id": 41,
        "user_id": 20,
        "created_by": 21,
        "created_at": "2021-03-19T21:29:49.000000Z",
        "updated_at": "2021-03-19T21:43:14.000000Z"
    }
}
`````

-  Delete an event
```
DELETE /api/events/41

Headers:
{
	Authorization: Bearer {token}
	Accept: application/json
}

Response:
{
    "success": true,
    "status_code": 200,
    "message": "Event deleted",
    "data": []
}
`````

-  Fetch event locations for a time frame. Same locations are not repeated.
```
GET /api/event-locations?from_date=2021-03-01&to_date=2021-03-30

Headers:
{
	Authorization: Bearer {token}
	Accept: application/json
}

Response:
{
    "success": true,
    "status_code": 200,
    "message": "Success",
    "data": [
        {
            "location": "London",
            "weather": {
                "description": "few clouds",
                "temperature": 4.9,
                "humidity": 75
            }
        },
        {
            "location": "Paris",
            "weather": {
                "description": "clear sky",
                "temperature": 4.53,
                "humidity": 56
            }
        }
    ]
}
`````

__Invitation Emails__

Invitation emails are sent to users when they get invited to an event. For this to happen you need to specify valid email server configurations in the .env file. For testing purposes I used Gmail SMTP client for sending invitation emails. You can use similar settings to test email sending on localhost.

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME={you gmail email address}
MAIL_PASSWORD={your gmail password}
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS={you gmail email address}
MAIL_FROM_NAME="${APP_NAME}"
`````

This is solely for testing on local. On production however, a more robust email service such as mailgun should be used. 


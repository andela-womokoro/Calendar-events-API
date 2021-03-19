
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
+ Cd in the application folder and run sail up command:

```
cd calendar-event-api

./vendor/bin/sail up
`````

The first time you run the Sail up command, Sail's application containers will be built on your machine. This could take several minutes. Don't worry, subsequent attempts to start Sail will be much faster.

+ Next run the database migrations to setup the database.

```
php artisan migrate
`````

With Laravel sail the command to run migrations should be

```
sail artisan migrate
`````
+ 


## Usage


__Request__


__Response__


## 


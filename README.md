
# Service B - user profile service

## Usage
This guide will walk you through the steps to set up a Laravel development environment using Docker. 

To get started, make sure you have [Docker installed](https://docs.docker.com/docker-for-mac/install/) on your system, and then clone this repository.

Step 1: Clone the Repository Clone your Laravel project repository

git clone https://github.com/nasrunafiqq/vimigo-service-B.git
- `cd vimigo-service-B`

Bringing up docker container by:
- `docker-compose up --build nginx`

Bringing down docker container by:
- `docker-compose down`

Three additional containers are included that handle Composer, NPM, and Artisan commands *without* having to have these platforms installed on your local computer. Use the following command examples from your project root, modifying them to fit your particular use case.

- `docker-compose run --rm composer update`
- `docker-compose run --rm npm run dev`
- `docker-compose run --rm artisan migrate`

## Laravel installation procedure

Install all the dependencies using composer
- `docker-compose run --rm composer install`

update all the dependencies using composer
- `docker-compose run --rm composer update`

Copy the example env file and make the required configuration changes in the .env file

- `cp .env.example .env`


Change database config in env
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret
```
Generate a new application key

- `docker-compose run --rm artisan key:migrate`

Run the database migrations (**Set the database connection in .env before migrating**)

- `docker-compose run --rm artisan migrate`

_**Note**_  : It's recommended to have a clean database before seeding. You can refresh your migrations at any point to clean the database by running the following command

- ``php artisan migrate:refresh``

run db seeder

- `docker-compose run --rm artisan db:seed`

## RabbitMQ

- Register an account at [here](https://www.cloudamqp.com/). choose the free tier
- create new instance
- add rabbitMQ config in env
- go to instance settings and fill in the config in env based on the instance settings 
- RABBITMQ_VHOST and RABBITMQ_QUEUE can be null
```
RABBITMQ_HOST=

RABBITMQ_PORT=5672

RABBITMQ_USER=

RABBITMQ_PASSWORD=

RABBITMQ_VHOST=

RABBITMQ_QUEUE=
```
- add rabbitMQ config in queue.php
```
'rabbitmq' => [

'driver' => 'rabbitmq',

'hosts' => [

		[

		'host' => env('RABBITMQ_HOST',  '127.0.0.1'),

		'port' => env('RABBITMQ_PORT',  5672),

		'user' => env('RABBITMQ_USER',  'guest'),

		'password' => env('RABBITMQ_PASSWORD',  'guest'),

		'vhost' => env('RABBITMQ_VHOST',  '/'),

		],

	],

],
```
-   change queue connection in env
`QUEUE_CONNECTION=rabbitmq`

- test the queue by running
`docker-compose run --rm artisan queue:work`
- dispatching job by running this command in service C
``docker-compose run --rm artisan update-user-profile``


## Testing API

_**Note**_  : It's recommended to make sure all the container (service A, Service B, Service C) is up and running first

to get playlist recommendation request data:
- ``http://localhost:80/api/user/playlist/1``

to get billing request data:
- ``http://localhost:80/api/user/billing/1``

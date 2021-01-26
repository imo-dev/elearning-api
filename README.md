# elearning-api
Restful Api for elearning build with laravel.

## Table Of Content
  + [Team Workspace](#team-workspace)
  + [First setup](#first-setup)
  + [Update](#update)
  + [Third-party Library Use](#third-party-library-use)
  + [Progress](#progress)
  + [Route List](#route-list)
  + [Tables](#tables)

## Team Workspace 
* Github Team
  ```
  https://github.com/imo-dev
  ```
* Jira
  ```
  https://imo-dev.atlassian.net/
  ```
* Postman - Api Document
  ```
  https://documenter.getpostman.com/view/6433725/TVzYguhi
  ```


## First setup
* clone repo
  ```
  git clone https://github.com/imo-dev/elearning-api
  ```
* install composer dependencies
  ```
  composer install
  ```
* copy `.env.example` to `.env`
  ```
  cp .env.example .env
  ```
* edit configuration in `.env`
  ```
  nano .env
  ```
* edit app, database config
  ```
  APP_URL="http://localhost:8000"
  APP_PORT=8000

  DB_CONNECTION=pgsql
  DB_HOST=postgres
  DB_PORT=5432
  DB_DATABASE=root
  DB_USERNAME=root
  DB_PASSWORD=root
  ```
* serving in docker
  ```
  ./vendor/bin/sail up
  ```
* wait until docker compose finish
* migrate database
  ```
  sail artisan migrate:fresh --seed
  ```
* prepare passport for auth
  ```
  sail artisan passport:install
  ```


## Update
* update repo
  ```
  git pull origin main
  ```
* update composer library
  ```
  composer install
  ```
* refresh database
  ```
  sail artisan migrate:fresh --seed
  sail artisan passport:install
  ```
* check new setup in README.md for new info everyday


## Third-party Library Use
* laravel/sail
* laravel/passport


## Progress
* [17 January 2021]
  - Configuration Database
  - Install Laravel Sail
  - Configuration Docker
* [18 January 2021]
  - Setup enviroment for api
    - adding helper
      (global-function, Version, DataTable)
    - setup api route
* [19 January 2021]
  - Setup auth api
  - Add admin courses crud
* [20 January 2021]
  - Add admin course categories crud
  - Add relation categories to course belongsToMany
* [21 January 2021]
  - Update table user add new role inspectors and instructors
  - Add user course
  - Add course inspectors
  - Add course instructors


## Route List
```
+-----------+---------------------------------------------------+--------------------+
| Method    | URI                                               | Name               |
+-----------+---------------------------------------------------+--------------------+
| GET|HEAD  | /                                                 |                    |
| GET|HEAD  | v1                                                |                    |
| GET|HEAD  | v1/admin/categories                               | categories.index   |
| POST      | v1/admin/categories                               | categories.store   |
| PUT|PATCH | v1/admin/categories/{category}                    | categories.update  |
| GET|HEAD  | v1/admin/categories/{category}                    | categories.show    |
| DELETE    | v1/admin/categories/{category}                    | categories.destroy |
| POST      | v1/admin/courses                                  | courses.store      |
| GET|HEAD  | v1/admin/courses                                  | courses.index      |
| GET|HEAD  | v1/admin/courses/{course}                         | courses.show       |
| PUT|PATCH | v1/admin/courses/{course}                         | courses.update     |
| DELETE    | v1/admin/courses/{course}                         | courses.destroy    |
| POST      | v1/admin/courses/{course}/assignment              | assignment.store   |
| GET|HEAD  | v1/admin/courses/{course}/assignment              | assignment.index   |
| DELETE    | v1/admin/courses/{course}/assignment/{assignment} | assignment.destroy |
| PUT|PATCH | v1/admin/courses/{course}/assignment/{assignment} | assignment.update  |
| GET|HEAD  | v1/admin/courses/{course}/assignment/{assignment} | assignment.show    |
| GET|HEAD  | v1/admin/courses/{course}/materials               | materials.index    |
| POST      | v1/admin/courses/{course}/materials               | materials.store    |
| PUT|PATCH | v1/admin/courses/{course}/materials/{material}    | materials.update   |
| DELETE    | v1/admin/courses/{course}/materials/{material}    | materials.destroy  |
| GET|HEAD  | v1/admin/courses/{course}/materials/{material}    | materials.show     |
| GET|HEAD  | v1/admin/courses/{course}/quiz                    | quiz.index         |
| POST      | v1/admin/courses/{course}/quiz                    | quiz.store         |
| DELETE    | v1/admin/courses/{course}/quiz/{quiz}             | quiz.destroy       |
| PUT|PATCH | v1/admin/courses/{course}/quiz/{quiz}             | quiz.update        |
| GET|HEAD  | v1/admin/courses/{course}/quiz/{quiz}             | quiz.show          |
| GET|HEAD  | v1/admin/courses/{course}/topics                  | topics.index       |
| POST      | v1/admin/courses/{course}/topics                  | topics.store       |
| GET|HEAD  | v1/admin/courses/{course}/topics/{topic}          | topics.show        |
| PUT|PATCH | v1/admin/courses/{course}/topics/{topic}          | topics.update      |
| DELETE    | v1/admin/courses/{course}/topics/{topic}          | topics.destroy     |
| POST      | v1/auth/login                                     |                    |
| GET|HEAD  | v1/auth/me                                        |                    |
| POST      | v1/auth/register                                  |                    |
+-----------+---------------------------------------------------+--------------------+
```


## Tables
![ss tables](https://raw.githubusercontent.com/imo-dev/elearning-api/main/.dev/table.svg)
* Laravel Default
  - failed_jobs
  - migrations
* Category
  - categories
  - courses
  - course_categories
  - course_inspectors
  - course_instructors
* Users
  - users
  - user_courses
  - user_login_histories
  - password_resets
* Passport - for auth
  - oauth_access_tokens
  - oauth_auth_codes
  - oauth_clients
  - oauth_personal_access_clients
  - oauth_refresh_tokens
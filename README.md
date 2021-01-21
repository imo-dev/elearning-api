# elearning-api
Restful Api for elearning build with laravel.

### Team Workspace 
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


### First setup
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


### Update
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


### Third-party Library Use
* laravel/sail
* laravel/passport


### Progress
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

### Tables
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
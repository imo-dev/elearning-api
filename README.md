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


### Update
* update repo
  ```
  git pull origin main
  ```
* update composer
  ```
  composer update
  ```
* check new setup in README.md for new info everyday


### Third-party Library Use
* laravel/sail


### Progress
* [17 January 2021]
  - Configuration Database
  - Install Laravel Sail
  - Configuration Docker
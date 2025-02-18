## Product Management API

### Steps to setup local environment

1. **Clone this project**
    - Run `git clone https://github.com/irfanasri96/collektr-assignment.git`.

2. **Install package**
    - Run `composer install` to install composer package.

3. **Setup enviroment**
    - Run `cp .env.example .env`.
    - Change the value for `APP_URL= `
    - Change the settings for database:
    ``` 
    DB_CONNECTION=mysql
    DB_HOST=
    DB_PORT=
    DB_DATABASE=
    DB_USERNAME=
    DB_PASSWORD=
    ```
4. **Install Application Key**
    - Run `php artisan key:generate`.

5. **Migrate and seed database**
    - Run `php artisan migrate:seed`.

6. **Clear cache**
    - Run `php artisan optimize`.

7. **Test login to the application**
    - Login test credentials
    ``` 
    email: test@example.com
    password: test123
    ```
8. **Added testing enviroment**
    - Run `cp .env.example .env.testing`.
    - Change the value for `APP_ENV=testing`
    - Change the settings for database:
    ``` 
    DB_CONNECTION=mysql
    DB_HOST=
    DB_PORT=
    DB_DATABASE=
    DB_USERNAME=
    DB_PASSWORD=
    ```

9. **Run test**
    - Run `php artisan test`
# Installing


* [Requirements](#requirements)
* [Step 1: Get the code](#step1)
* [Step 2: Use Composer to install dependencies](#step2)
* [Step 3: Create database](#step3)
* [Step 4: Install](#step4)
* [Troubleshooting](#troubleshooting)

<a name="requirements"></a>
## Requirements

* PHP ^7.3|^8.0
* MySQL server

<a name="step1"></a>
## Step 1: Get the code

    git clone https://github.com/legolas237/stream_api.git
    cd stream_api

## Step 2: Install dependencies with Composer

    composer install

## Step 3: Create the Database

Once you finished the first two steps, you can create the *MySQL* database server. You must create the database with `utf-8` collation (`utf8_general_ci`), for the application to work.  As admin login to *MySQL* database and then execute the following:

    CREATE DATABASE o_stream_dev
    CREATE USER 'o_stream_dev'@'localhost' IDENTIFIED BY '<password>';
    GRANT ALL PRIVILEGES ON o_stream_dev.* TO'o_stream_dev'@'localhost';

## Step 4: Configure the Environment

**Copy** the **.env.example** file to **.env**

    cp .env.example .env

**Edit** the `.env` file and set the database configuration among the other settings.

Set database infomation

    DB_DATABASE=o_stream_dev
    DB_USERNAME=o_stream_dev
    DB_PASSWORD=<password>

Clear configuration cache

     php artisan config:cache

Regenerate Composer's autoloader using the *dump-autoload* command

    composer dump-autoload

Set the application key

    php artisan key:generate
    php artisan jwt:secret

**Create** new schema in your database.  **Note**: make sure that your run *php artisan config:cache* and *composer dump-autoload* prior to executing any migration or seed steps

    php artisan migrate

**Populate** the database. **Note**: run seed only if you have not migrated old data from the previous step.

    php artisan db:seed

And we are ready to go. **Run** the server:

    php artisan serve

**Type** on web browser:

    http://localhost:8000/

Congrats! You have the running server

## Troubleshooting
### Memory limit errors

Composer may sometimes fail on some commands with this message:

    PHP Fatal error: Allowed memory size of XXXXXX bytes exhausted <...>

Try increasing the limit in your php.ini file (ex. */etc/php5/cli/php.ini* for Debian-like systems):

    ; Use -1 for unlimited or define an explicit value like 2G
    memory_limit = -1

Composer also respects a memory limit defined by the COMPOSER_MEMORY_LIMIT environment variable:

    export COMPOSER_MEMORY_LIMIT=-1
    composer <...>

# Manage Subscribers Mini-Project

This is a mini-project built with Laravel and MailerLite API for managing subscribers.

## Features

As an admin user, you can:

-   View all subscribers
-   Create new subscribers
-   Edit name and country for existing subscribers
-   Delete subscribers (note that there is no confirmation message for deletion)

This project also uses DataTables to allow users to:

-   Search the subscriber data
-   Change the number of subscribers shown per page

## Installation

1. Install dependencies by running `composer install`.
2. Create a `.env` file by copying the `.env.example` file and setting the database name and the `MAILER_LITE_API_KEY` variable value to use MailerLite services.
3. Run the following two commands to migrate and seed the database:
    - `php artisan migrate`
    - `php artisan db:seed`

## Usage

1. Start the server by running `php artisan serve`.
2. Go to http://127.0.0.1:8000/subscribers (using default host and port) to view the index of subscribers.
3. To login as an admin user, go to http://127.0.0.1:8000/admin/login (using default host and port) and use `admin` as the username and `password` as the password.

# Project Details

This document outlines the requirements and implementation details are :
login | register | profile page,

# Apis Lists

1. Login 
2. Register
3. Profile

# What all thing i have used  while Developing Apis Lists;

Application layer  :  Req->Controller->Service->model->response;

1. Jwt Authentication
2. Resources
3. Response Structure

## Requirements to run the Projects 

- PHP version 7.3 or higher, or PHP version 8.0
- Laravel Framework version 8.75
- Composer (Dependency Manager for PHP)

## Installation

1. **Clone the repository:**

- move to backend 
- cd deployd

git clone Links:
1. composer install
2. cp .env.example .env

- Please update below details in .env file
    JWT_KEY=something
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=deployd
    DB_USERNAME=postgres
    DB_PASSWORD=postgres

3. php artisan key:generate
4. php artisan migrate
5. php artisan serve --port=8000

# Collection Details 
# file i have mention in Emails.















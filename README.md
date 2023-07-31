# API Book Manager Documentation

Welcome to the API Book Manager documentation! This API allows you to manage books by performing basic operations such as creating, reading, updating, and deleting books.

## Prerequisites

Before you can use the API Book Manager, please ensure that you have the following:

1. **PHP and Symfony:** Make sure you have PHP installed on your system. You'll also need to have Symfony installed to run the API. You can check the Symfony documentation for installation instructions: [Symfony Installation Guide](https://symfony.com/doc/current/setup.html)

2. **Database:** The API Book Manager uses a MySQL database to store book information. Please make sure you have a MySQL server set up and running. You'll need to configure the database connection in the `.env` file of your Symfony project.

3. **Composer:** Composer is a dependency management tool for PHP. You'll need Composer to install the required packages for the API. You can download Composer from their official website: [Composer Download](https://getcomposer.org/download/)

## Installation and Usage

To start using the API Book Manager, follow these steps:

1. Install the required dependencies using Composer:

composer install

2. Set up the database connection in the `.env` file:

DATABASE_URL="mysql://your-username:your-password@localhost:3306/your-database-name"

3. Create the database schema:

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

4. Start the Symfony server:

symfony serve

## Base Endpoint

The base URL for the API is: `localhost/api`

## Available Endpoints

### Get the list of all existing books.

Method: GET
Route: /books

### Get the details of a book based on its identifier (`id`).

Method: GET
Route: /books/{id}

#### Parameters

- id
  - Integer
  - Required

### Create a new book with the information provided in the request body. The required fields are `title`, `author`, and `publication_year`.

Method: POST
Route: /books

#### Parameters

- Request Body
  - title
    - String
    - 1 to 255 characters
    - Required
  - author
    - String
    - 1 to 255 characters
    - Required
  - publication_year
    - Integer
    - 1 to Current Year
    - Required

### Update the information of an existing book based on its identifier (`id`). The editable fields are `title`, `author`, and `publication_year`.

Method: PUT
Route: /books

#### Parameters

- id
  - Integer
  - Required

- Request Body
  - title
    - String
    - 1 to 255 characters
    - Optional
  - author
    - String
    - 1 to 255 characters
    - Optional
  - publication_year
    - Integer
    - 1 to Current Year
    - Optional

### Delete a book based on its identifier (`id`).

Method: DELETE
Route: /books/{id}

#### Parameters

- id
  - Integer
  - Required

## Status Codes

- `200 OK`: Request successful.
- `201 Created`: A new record has been created successfully.
- `400 Bad Request`: The request is malformed, or required fields are missing or invalid.
- `404 Not Found`: Resource not found.


# TickTrack

TickTrack is a ticket management API built with Laravel 11. It provides functionality for managing tickets with user roles, including managers and authors. The project uses various tools from the Laravel ecosystem such as Sanctum for authentication token generation and Scribe for API documentation.

## Features

- User management with roles (Manager and Author)
- Ticket management
- API authentication using Laravel Sanctum
- API documentation using Scribe

## Installation

Follow these steps to set up the project on your local machine.

### Prerequisites

- PHP >= 8.1
- Composer
- MySQL or any other supported database

### Steps

1. **Clone the repository:**

   ```sh
   git clone https://github.com/paulo93f/TickTrack.git
   cd TickTrack
   ```

2. **Install dependencies:**

   ```sh
   composer install
   ```

3. **Set up the environment:**

   Copy the `.env.example` file to `.env`:

   ```sh
   cp .env.example .env
   ```

   Update the `.env` file with your database and other configurations:

4. **Generate the application key:**

   ```sh
   php artisan key:generate
   ```

5. **Run the migrations:**

   ```sh
   php artisan migrate
   ```

6. **Seed the database:**

   If you want to set up your database with initial Users, Tickets and their relations, run:

   ```sh
   php artisan db:seed
   ```

7. **Serve the application:**

   ```sh
   php artisan serve
   ```

   The application will be accessible at `http://localhost:8000`.

## API Documentation

The API documentation is generated using Scribe. To view the documentation, ensure the application is running and visit:
```
http://localhost:8000/docs
```
In case you want to generate new documentation with your changes, run:
```
php artisan scribe:generate  
```

## Authentication

This application uses Laravel Sanctum for API token authentication. To use the API endpoints, include your token in the `Authorization` header as follows:

```
Authorization: Bearer your_token
```

## Postman collection

I made a postman collection for this API, so you don't have to 😜.

```
storage/postman/TicketTrack.postman_collection.json
```
If you don't know how to import, read the [official documentation](https://learning.postman.com/docs/getting-started/importing-and-exporting/importing-and-exporting-overview/) for the current version.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

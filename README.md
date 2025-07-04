## Exchange Rates API

This project is calling an External API, parsing and persisting results in a MySQL database

## Features

- Fetching rates and storing them to the database
- There is an endpoint that return all stored records with pagination and filters
- There is an endpoint that return the information of a specific record

## Setup the project

1. Clone the repository
- You can clone the repository either using HTTPS (`git clone https://github.com/manthosmav/ferryscanner.git`) or SSH (`git clone git@github.com:manthosmav/ferryscanner.git`)
2. Install dependencies using composer 
- Use `composer install` to install mandatory dependencies. If you dont have a composer please refer to this link (`https://getcomposer.org/download/`) and download the composer
3. The .env installation
- Copy .env.example file using the following command `cp .env.example .env`
- Set up a database in the .env file
`DB_DATABASE=database_name DB_USERNAME=root DB_PASSWORD=root`
4. Generate the application ID
- `php artisan key:generate`
5. Run migration
- `php artisan migrate`
6. Serve the app
- `php artisan serve`

## API Endpoints

1. `api/rates`
- This API fetches the data from ecb.europa endpoint and return the data in a structured way
2. `api/store-rates`
- This API stores in the database the response data from ecb.europa endpoint
3. `api/stored-rates`
- This API returns the data from the database and has filtering
    - Available filters are:
        1. `currency_to` (e.g USD)
        2. `retreived_at` (format: yyyy-mm-dd)
            - Examples
                - `retreived_at=2025`
                - `retreived_at=2025-07`
                - `retreived_at=2025-07-04`
        3. `min_rate`
        4. `max_rate`
        5. `per_page`
        6. `page`
    - Example `api/stored-rates?page=1&per_page=5&currency_to=USD&retreived_at&max_rate=5&min_rate=0.5`

4. `api/stored-rates/{id}`
- This API returns a specific exchange rate

## Thank you
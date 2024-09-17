# CO2 Savings Calculation API

This project provides a set of RESTful APIs to calculate and display the total CO2 savings from the sales of synthetic meat products by the startup Kréas. The APIs support filtering by date range, destination country, and product type to allow for detailed analysis of the environmental impact of product sales.

## Features
- **CO2 Savings Calculation**: Calculates the total CO2 savings from product sales.
- **Dynamic Filtering**: Allows filtering by date range, destination country, and product.
- **RESTful Design**: Follows RESTful API principles for intuitive use.
- **Data Validation**: Ensures valid data input for accurate results.
- **Error Handling**: Robust error handling with user-friendly messages.

## Endpoints
- `GET /api/saved-co2`: Retrieve total CO2 savings.
- `GET /api/saved-co2?product_id=:id`: Filter CO2 savings by product.
- `GET /api/saved-co2?start_date=:start_date&end_date=:end_date`: Filter CO2 savings by date range.
- `GET /api/saved-co2?destination_country=:country`: Filter CO2 savings by destination country.

## Getting Started
### Prerequisites
- PHP 8.x
- Laravel 9.x
- MySQL or another supported database
- Composer

### Installation
1. Clone the repository:
    ```bash
    git clone https://github.com/ra1nb93/co2-savings-api.git
    cd co2-savings-api
    ```

2. Install dependencies:
    ```bash
    composer install
    ```

3. Set up the `.env` file:
    ```bash
    cp .env.example .env
    ```
    Configure your database settings in the `.env` file.

4. Generate the application key:
    ```bash
    php artisan key:generate
    ```

5. Run database migrations:
    ```bash
    php artisan migrate
    ```

6. Seed the database with test data (optional):
    ```bash
    php artisan db:seed
    ```

7. Start the development server:
    ```bash
    php artisan serve
    ```

### Usage
Use a tool like Postman or cURL to test the API endpoints.

### Testing
Run the API tests using PHPUnit:
```bash
php artisan test
```

## License
This project is licensed under the MIT License.

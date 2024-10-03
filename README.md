# Truck-Subunit Backend

This is a Laravel-Based Backend for managing trucks and subunits with a set of RESTful endpoints. 
## Requirements
- Stable version of [Docker](https://docs.docker.com/engine/install/)
- Compatible version of [Docker Compose](https://docs.docker.com/compose/install/#install-compose)

## How To Launch

### For First Time Setup:
1. **Clone the repository**:
    ```bash
    git clone https://github.com/giezele/truck-subunit.git
    cd truck-subunit
    ```

2. **Setup environment variables**:
    - Copy the `.env.example` file to `.env` and edit the database credentials:
    ```bash
    cp .env.example .env
    ```
    - Set your database credentials in the `.env` file:
      ```env
      DB_DATABASE={your db name}
      DB_USERNAME={your username}
      DB_PASSWORD={your password}
      ```

3. **Use Docker to run `composer install` via Laravel Sail**:
    ```bash
    docker run --rm -u "$(id -u):$(id -g)" -v $(pwd):/var/www/html -w /var/www/html laravelsail/php82-composer:latest composer install
    ```

4. **Start the Sail environment**:
    ```bash
    ./vendor/bin/sail up -d --build
    ```

5. **Run migrations**:
    ```bash
    ./vendor/bin/sail artisan migrate
    ```

6. **To access the Sail container's shell** (optional for artisan commands):
    ```bash
    ./vendor/bin/sail shell
    ```

### For Subsequent Launches:
1. **Start the containers**:
    ```bash
    docker compose up -d
    ```

2. **To access the Sail container's shell** (optional for artisan commands):
    ```bash
    ./vendor/bin/sail shell
    ```

## Testing

**Feature Tests**: The project includes feature tests for CRUD operations on trucks and the assignment of subunits. These tests ensure that the application’s functionality works as expected. You can run the tests using:
   ```bash
   ./vendor/bin/sail test
```

## Endpoints for Testing with Postman

### 1. **Create a Truck**:
**URL**: `http://localhost/api/trucks`  
**Method**: POST  
**Body (JSON)**:
   ```json
   {
     "unit_number": "A1234",
     "year": 2022,
     "notes": "Available for rent"
   }
   ```
### 2. **Get All Trucks**:
   **URL**: `http://localhost/api/trucks`  
   **Method**: `GET`

### 3. **Update a Truck**:
   **URL**: `http://localhost/api/trucks/{id}`  
   **Method**: `PUT`  
   **Body (JSON)**:
   ```json
   {
     "unit_number": "B5678",
     "year": 2023,
     "notes": "Updated notes"
   }
```
### 4. **Delete a Truck**:
**URL**: `http://localhost/api/trucks/{id}`  
**Method**: `DELETE`  

### 5. **Assign a Subunit**:
**URL**: `http://localhost/api/truck-subunits`  
**Method**: `POST`  
**Body (JSON)**:
   ```json
   {
    "main_truck": 1,
    "subunit": 2,
    "start_date": "2024-10-01",
    "end_date": "2024-10-10"
}
```

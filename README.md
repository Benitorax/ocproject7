# Project as part of OpenClassrooms training

The project is developed with Symfony, its components and bundles. It's an API application (built without API Platform) where users are business companies who has customers.

Users can use endpoints to:

-   log in (return a JWT).
-   view a list of customers with pagination.
-   view detailed informations about a customer.
-   add a new customer.
-   delete a customer.
-   view a list of mobile phones
-   view detaild informations about a mobile phone.

Only logged users can use those endpoints.

## Getting started
### Step 1: Configure environment variables
Copy the `.env file` in project directory, rename it to `.env.local` and configure the following variables for:
-   the database:
```false
DATABASE_URL=
 ```

### Step 2: Install components and librairies
Run the following command:
```false
composer install
```

### Step 3: Create database and tables
Run the following command:
```false
php bin/console doctrine:database:create
php bin/console doctrine:schema:create
```

### Step 4: Create fixtures
Run the following command:
```false
php bin/console doctrine:fixtures:load
```

### Step 5: Generate public/private keys for authentication
Run the following command:
```false
php bin/console lexik:jwt:generate-keypair
```

### Step 6: Launch the server
Run the following command:
```false
php -S 127.0.0.1:8000 -t public
```

Or with Symfony CLI:
```false
symfony serve -d
```

### Step 7: View endpoints
To see the list of available endpoints, go to: `127.0.0.1:8000/api/doc`.

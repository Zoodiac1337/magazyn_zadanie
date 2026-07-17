--- How to initiate the webapp ---
1. Install dependencies ->    composer install
2. Start the database in a container ->    docker compose up -d
3. Run database migrations ->    php bin/console doctrine:migrations:migrate --no-interaction
5. Run the symfony web server ->    symfony serve
6. Uncomment 13th line in src/Controller/TestUsersController.php to allow access to create first set of users
7. Navigate to http://localhost:8000/test-users in a browser to create first set of users

The first set of users consists of:
   Basic user ->    username: user | password: user123
   Admin user ->    username: admin| password: admin123

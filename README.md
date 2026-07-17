--- How to initiate the webapp ---
1. Start the database in a container ->    docker compose up -d
2. Run the symfony webapp ->    symfony serve
3. Uncomment 13th line in src/Controller/TestUsersController.php to allow access to create first set of users
4. Run http://localhost:8000/test-users in a browser.

The first set of users consists of a:
   Basic user ->    username: user, password: user123
   Admin user ->    username: admin, password: admin123

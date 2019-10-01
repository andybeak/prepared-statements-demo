# Prepared statements

This project demonstrates using PDO prepared statements as a way
to mitigate SQL injection attacks.

There are two methods in the `App\Main.php` file.  We run each function with each of the parameters:

    $parameters = [
        // expected parameter
        'Jacob',
        // sql injection attack example
        "Jacob' OR 1=1;"
    ];

The method `unsafeQuery()` is vulnerable to SQL injection.  When we pass the second
parameter (the example injection) the method will return all of the users. 

## Running the project

The program runs in Docker which will create the database.  The `App\Seed` class will add the table and seed it with some users.  This means you do not need to run this against a real database.

Use the following commands to run the project: 

    cd docker
    sudo docker-compose up -d
    docker exec -it php /bin/bash    
    composer install
    php index.php
    
To bring the docker stack down once you've finished, run the following commands.  I'm assuming that the terminal is still in the `php` docker container which is why you need to `exit` first. 
    
    exit
    docker-compose down
    
Example output:

     **** [Jacob] ****
    ----- Executing query [SELECT * FROM users WHERE username = 'Jacob'] in unsafe mode
    List of users:
    * Jacob
    ----- Executing query [SELECT * FROM users WHERE username = :username] with prepared statement
    List of users:
    * Jacob
    ----- Executing query [SELECT * FROM users WHERE username = 'Jacob'] while escaping the parameter
    List of users:
    * Jacob
    
     **** [Jacob' OR 1=1;] ****
    ----- Executing query [SELECT * FROM users WHERE username = 'Jacob' OR 1=1;'] in unsafe mode
    List of users:
    * Jacob
    * Schabir
    * Tony
    * Atul
    ----- Executing query [SELECT * FROM users WHERE username = :username] with prepared statement
    List of users:
    ----- Executing query [SELECT * FROM users WHERE username = 'Jacob\' OR 1=1;'] while escaping the parameter
    List of users:


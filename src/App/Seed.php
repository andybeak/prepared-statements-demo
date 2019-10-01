<?php

namespace App;

class Seed
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @param string $databaseName
     * @param string $user
     * @param string $password
     */
    public function createDatabase(string $databaseName, string $user, string $password): void
    {
        $sqlStatements = [
            'CREATE TABLE users (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, username VARCHAR(20))',
            'TRUNCATE TABLE users',
            'GRANT ALL PRIVILEGES ON ' . $databaseName . '.* TO ' . $user . ' IDENTIFIED BY ' . $password
        ];

        foreach ($sqlStatements as $sqlStatement) {
            // Note: I am not escaping the strings because I'm assuming a hacker can't interfere with my docker environment
            $this->pdo->query($sqlStatement);
        }
    }

    /**
     * Add some users
     */
    public function addSomeUsers(): void
    {
        $users = ['Jacob', 'Schabir', 'Tony', 'Atul'];
        $sql = "INSERT INTO users (username) VALUES (:username)";
        $statement = $this->pdo->prepare($sql);
        foreach ($users as $user) {
            // a side effect of using prepared statements is that it is faster to make repeated queries of the same SQL
            $statement->execute([':username' => $user]);
        }
    }

    /**
     * @param \PDO $pdo
     */
    public function setPDO(\PDO $pdo): void
    {
        $this->pdo = $pdo;
    }
}
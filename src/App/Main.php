<?php

namespace App;

class Main
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * This function is vulnerable to SQL injection
     * @param string $username
     */
    public function unsafeQuery(string $username): void
    {
        $sql = "SELECT * FROM users WHERE username = '$username'";
        echo "----- Executing query [$sql] in unsafe mode" . PHP_EOL;
        $result = $this->pdo->query($sql);
        if (false === $result) {
            print_r($this->pdo->errorInfo());
            exit;
        }
        echo "List of users:" . PHP_EOL;
        foreach ( $result as $row) {
            echo '* ' . $row['username'] . PHP_EOL;
        }
    }

    /**
     * Prepared statements are the strongest defence
     * @param string $username
     */
    public function preparedStatement(string $username): void
    {
        $sql = "SELECT * FROM users WHERE username = :username";
        echo "----- Executing query [$sql] with prepared statement" . PHP_EOL;
        $statement = $this->pdo->prepare($sql);
        $statement->execute([':username' => $username]);
        $values = $statement->fetchAll();
        echo "List of users:" . PHP_EOL;
        foreach ($values as $value) {
            echo '* ' . $value['username'] . PHP_EOL;
        }
    }

    /**
     * Escaping strings should only be used if you can't use a prepared statement.
     * Each type of database needs different escaping logic so use PDO::quote
     * If you can't use PDO::quote then make sure you use the correct escape function for your database
     * @param string $username
     */
    public function escapeStrings(string $username): void
    {
        $username = $this->pdo->quote($username);
        $sql = "SELECT * FROM users WHERE username = $username";
        echo "----- Executing query [$sql] while escaping the parameter" . PHP_EOL;
        $result = $this->pdo->query($sql);
        if (false === $result) {
            print_r($this->pdo->errorInfo());
            exit;
        }
        echo "List of users:" . PHP_EOL;
        foreach ( $result as $row) {
            echo '* ' . $row['username'] . PHP_EOL;
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
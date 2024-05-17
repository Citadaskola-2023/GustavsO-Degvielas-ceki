<?php

namespace App;
use PDO;
use PDOException;

class Database
{
    public const BANNED_WORDS = ['DROP', 'INSERT', '<', '>'];
    public function connect(): PDO
    {
        try {
            $connection = new PDO(
                'mysql:host=mysql;dbname=fuel;',
                'root',
                'root',
                [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
            );
            $this->ensureTableExists($connection);
            return $connection;
        } catch (PDOException $exception) {
            echo $exception->getCode() . " " . $exception->getMessage() . '<br>';
            die("An error occurred while connecting to the database.");
        }
    }

    private function ensureTableExists(PDO $connection): void
    {
        $checkTableQuery = 'SHOW TABLES LIKE "Form"';
        $result = $connection->query($checkTableQuery);

        if ($result->rowCount() === 0) {
            $createQuery = "CREATE TABLE Form (
                id INT AUTO_INCREMENT PRIMARY KEY,
                license_plate VARCHAR(20) NOT NULL,
                date_time DATETIME NOT NULL,
                odometer INT NOT NULL,
                petrol_station VARCHAR(100) NOT NULL,
                fuel_type VARCHAR(32) NOT NULL,
                refueled DECIMAL(10,2) NOT NULL,
                fuel_price DECIMAL(10,4) NOT NULL,
                currency CHAR(3) NOT NULL,
                total DECIMAL(10,2) NOT NULL
            )";
            $connection->exec($createQuery);
        }
    }
}

<?php
require 'config.php';

class Database {
    private static $pdo;

    public static function getConnexion() {
        if (self::$pdo === null) {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                error_log('Connexion BD : ' . $e->getMessage());
                http_response_code(503);
                exit('Le service est temporairement indisponible.');
            }
        }
        return self::$pdo;
    }
}
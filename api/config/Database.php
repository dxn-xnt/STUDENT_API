<?php
require __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

class Database
{
    private static $instance = null;
    private $databaseConnection;
    private $host;
    private $databaseName;
    private $userName;
    private $password;

    private function __construct()
    {
        $this->host = $_ENV['DB_HOST'] ?? 'localhost';
        $this->databaseName = $_ENV['DB_NAME'] ?? 'test_db';
        $this->userName = $_ENV['DB_USER'] ?? 'root';
        $this->password = $_ENV['DB_PASS'] ?? '';

        try {
            $this->databaseConnection = new PDO(
                "mysql:host={$this->host};dbname={$this->databaseName}",
                $this->userName,
                $this->password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            $this->databaseConnection->exec("SET NAMES utf8");
        } catch (PDOException $exception) {
            error_log("Database Connection Error: " . $exception->getMessage());
            die(json_encode(["error" => "Database connection failed. Contact administrator."]));
        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->databaseConnection;
    }
}

<?php
class Database {
    private static $conn;
    public static $db_host = "localhost";
    public static $db_user = "root";
    public static $db_password = "Toor@12345";
    private static $dbname = "foobar";

    public static function getConnection() {
        if (!isset(self::$conn)) {
            self::$conn = new mysqli(self::$db_host, self::$db_user, self::$db_password, self::$dbname);
            if (self::$conn->connect_error) {
                die("Connection failed: " . self::$conn->connect_error);
            }
        }
        return self::$conn;
    }

    public static function closeConnection() {
        if (isset(self::$conn)) {
            self::$conn->close();
            self::$conn = null;
        }
    }

    public static function prepare($sql) {
        $conn = self::getConnection();
        return $conn->prepare($sql);
    }

    public static function beginTransaction() {
        $conn = self::getConnection();
        $conn->autocommit(false);
    }

    public static function commit() {
        $conn = self::getConnection();
        $conn->commit();
        $conn->autocommit(true);
    }

    public static function rollback() {
        $conn = self::getConnection();
        $conn->rollback();
        $conn->autocommit(true);
    }
}
?>
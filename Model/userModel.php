<?php
class UserModel {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function createTable() {
        $conn = $this->db->getConnection(); // Assuming getConnection() returns the mysqli connection object

        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(30) NOT NULL,
            surname VARCHAR(30) NOT NULL,
            email VARCHAR(50) NOT NULL UNIQUE
        )";

        if (mysqli_query($conn, $sql)) {
            echo "Users table created successfully\n";
        } else {
            echo "Error creating users table: " . mysqli_error($conn) . "\n";
        }

        $this->db->closeConnection(); // Assuming closeConnection() closes the mysqli connection
    }

    public function createUser($name, $surname, $email)
{
    $stmt = $this->db->prepare("INSERT INTO users (name, surname, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $surname, $email);
    $result = $stmt->execute();
    if (!$result) {
        // Log the error or handle it as needed
        error_log("Error creating user: " . $stmt->error);
    }
    $stmt->close();
    return $result;
}
}
?>
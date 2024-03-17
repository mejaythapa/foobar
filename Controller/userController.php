<?php
require_once 'Model/userModel.php';
require_once 'Helper/validationHelper.php';

class userController
{
    private $userModel;

    public function __construct(Database $db)
    {
        $this->userModel = new UserModel($db);
    }

    public function processRequest(string $file): void
    {
        $csv = array_map('str_getcsv', file($file));
        $errors = [];
        $unsuccessfulIds = [];
        $hasErrors = false; // Flag to track if any error occurred
    
        foreach ($csv as $index => $row) {
            if (count($row) !== 3) {
                $errors[] = "Row $index: Invalid CSV format: " . implode(', ', $row);
                $hasErrors = true; // Set the flag to true
                continue;
            }
    
            $name = ucfirst(strtolower($row[0]));
            $surname = ucfirst(strtolower($row[1]));
            $email = strtolower($row[2]);
    
            if (!validationHelper::validateEmail($email)) {
                $errors[] = "Row $index: Invalid email format: $email";
                $hasErrors = true; // Set the flag to true
                continue;
            }
    
            if (!$this->userModel->createUser($name, $surname, $email)) {
                $errors[] = "Row $index: Error inserting user: $name, $surname, $email";
                $unsuccessfulIds[] = $index;
                $hasErrors = true; // Set the flag to true
            }
        }
    
        if ($hasErrors) {
            // Display errors and exit without saving data
            echo "Errors:\n";
            foreach ($errors as $error) {
                echo "- $error\n";
            }
            return;
        }
    
        // If no errors occurred, display success message and continue with further processing
        echo "All rows imported successfully.\n";
    
        if (!empty($unsuccessfulIds)) {
            echo "Unsuccessful IDs:\n";
            foreach ($unsuccessfulIds as $id) {
                echo "- Row $id\n";
            }
        }
    }
    
}
?>
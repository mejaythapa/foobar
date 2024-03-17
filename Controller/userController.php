<?php
require_once 'Model/userModel.php';
require_once 'Helper/validationHelper.php';

class UserController
{
    private $userModel;
    private $db;

    /**
     * UserController constructor.
     * @param Database $db The database connection instance.
     */

    public function __construct(Database $db)
    {
        $this->userModel = new UserModel($db);
        $this->db = $db;
    }
      /**
     * Processes the CSV file containing user data.
     * Validates the CSV format and user emails, then inserts valid user data into the database.
     * Rolls back the transaction if any error occurs during processing.
     *
     * @param string $file The path to the CSV file.
     */

     public function processRequest(string $file, bool $dryRun = false): void
     {
        $this->db->beginTransaction();

        try {
            $csv = array_map('str_getcsv', file($file));
            $errors = [];
            $unsuccessfulIds = [];

            foreach ($csv as $index => $row) {
                if (count($row) !== 3) {
                    $errors[] = "Row $index: Invalid CSV format: " . implode(', ', $row);
                    continue;
                }

                $name = ucfirst(strtolower($row[0]));
                $surname = ucfirst(strtolower($row[1]));
                $email = strtolower($row[2]);

                if (!validationHelper::validateEmail($email)) {
                    $errors[] = "Row $index: Invalid email format: $email";
                    continue;
                }
                if (!$dryRun && !$this->userModel->createUser($name, $surname, $email)) {
                    $errors[] = "Row $index: Error inserting user: $name, $surname, $email";
                    $unsuccessfulIds[] = $index;
                }
            }

            if (!empty($errors)) {
                echo "Errors:\n";
                foreach ($errors as $error) {
                    echo "- $error\n";
                }
            } else {
                echo "All rows imported successfully.\n";
            }

            if (!empty($unsuccessfulIds)) {
                echo "Unsuccessful IDs:\n";
                foreach ($unsuccessfulIds as $id) {
                    echo "- Row $id\n";
                }
            }

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollback();
            echo "Transaction failed: " . $e->getMessage() . "\n";
        }
    }

    public function processDryRun(string $file): void
{
    $csv = array_map('str_getcsv', file($file));

    foreach ($csv as $index => $row) {
        if (count($row) !== 3) {
            echo "Row $index: Invalid CSV format: " . implode(', ', $row) . "\n";
            continue;
        }

        $name = ucfirst(strtolower($row[0]));
        $surname = ucfirst(strtolower($row[1]));
        $email = strtolower($row[2]);

        if (!validationHelper::validateEmail($email)) {
            echo "Row $index: Invalid email format: $email\n";
            continue;
        }

        echo "Row $index: $name, $surname, $email\n";
    }
}

}

?>
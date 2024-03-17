<?php
require_once 'Model/userModel.php';
require_once 'Helper/validationHelper.php';

class UserController
{
    private $userModel;
    private $db;

    public function __construct(Database $db)
    {
        $this->userModel = new UserModel($db);
        $this->db = $db;
    }

    public function processRequest(string $file): void
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

                if (!$this->userModel->createUser($name, $surname, $email)) {
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
}

?>
<?php

require_once 'Controller/userController.php';
require_once 'database.php';
require_once 'Model/userModel.php';

$options = getopt("u:p:h:", ["file:", "create_table", "dry_run", "help"]);

$db_host = Database::$db_host;
$db_user = Database::$db_user;
$db_password = Database::$db_password;
// Display help message
if (isset($options['help'])) {
    echo "Usage: php user_upload.php [options]\n";
    echo "Options:\n";
    echo "  --file [csv file name]  Name of the CSV file to be parsed\n";
    echo "  --create_table          Build the MySQL users table and exit\n";
    echo "  --dry_run               Run the script without inserting into the DB\n";
    echo "  -u                      MySQL username\n";
    echo "  -p                      MySQL password\n";
    echo "  -h                      MySQL host\n";
    echo "  --help                  Display this help message\n";
    exit;
}

// Process command line options
if (isset($options['create_table'])) {
    if (isset($options['h']) && isset($options['u']) && isset($options['p'])) {
        // Use the provided database credentials
        $db = new Database($options['h'], $options['u'], $options['p']);
    } else {
        // Use the credentials from database.php
        $db = new Database($db_host, $db_user, $db_password);
    }
    print_r($db);
    $userModel = new UserModel($db);
    $userModel->createTable();
    exit;
}


?>

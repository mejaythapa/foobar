<?php
use PHPUnit\Framework\TestCase;

require_once 'Controller/userController.php';
require_once 'database.php';
require_once 'Model/userModel.php';

class UserControllerTest extends TestCase
{
    public function testProcessRequest()
    {
        // Create a mock database object
        $db = $this->getMockBuilder(Database::class)
                   ->getMock();

        // Mock the createUser method of UserModel to return true
        $userModel = $this->getMockBuilder(UserModel::class)
                          ->disableOriginalConstructor()
                          ->getMock();
        $userModel->expects($this->any())
                  ->method('createUser')
                  ->willReturn(true);

        // Instantiate the UserController with the mock objects
        $controller = new UserController($db, $userModel);

        // Call the processRequest method with a valid CSV file
        $this->expectOutputString("All rows imported successfully.\n");
        $controller->processRequest('valid.csv');

        // Call the processRequest method with an invalid CSV file
        $this->expectOutputString("Errors:\n- Row 0: Invalid CSV format: \n");
        $controller->processRequest('invalid.csv');

        // Mock the createUser method of UserModel to return false
        $userModel->expects($this->any())
                  ->method('createUser')
                  ->willReturn(false);

        // Call the processRequest method with a CSV file that fails to insert a user
        $this->expectOutputString("Errors:\n- Row 0: Error inserting user: John, Doe, john.doe@example.com\n");
        $controller->processRequest('failed.csv');
    }
}

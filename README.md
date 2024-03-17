# User Upload Script

This PHP script allows you to process a CSV file containing user data and insert it into a MySQL database. It provides several command-line options for different functionalities.

## Available Options

- `--file [csv file name]`: Specifies the CSV file to be parsed.
- `--create_table`: Builds the MySQL users table and exits.
- `--dry_run`: Runs the script without inserting into the database.
- `-u`: Specifies the MySQL username.
- `-p`: Specifies the MySQL password.
- `-h`: Specifies the MySQL host.
- `--help`: Displays the help message.

## Usage

### Build Users Table
To create the users table in the MySQL database, run the following command:

- php user_upload.php --create_table => if you put your mysql credential on database.php else
- php user_upload.php --create_table -h:host -u:username -p:password

simillarly to import csv file
- php user_upload.php --file /pathOfCsv => if you put your mysql credential on database.php else
- php user_upload.php ----file /pathOfCsv -h:host -u:username -p:password

# Foobbar.php logic
To run foobar logic test run the following command:
- php foobar.php
InjectEngine
========

This is the Inject Engine used for UBNETDEF's Cyber Security Competition.

## Requirements

* PHP 5.4
* MySQL
* Composer

## Installation

1. Rename the file "Config/database.php.default" to "Config/database.php"
2. Edit "Config/database.php" and enter the appropriate database credentials
3. Run ```composer install``` to install the project dependencies
4. Run ```./Console/cake schema create``` to create the database
5. Point your webroot to the directory "webroot"
6. You're done! The username and password to login is __admin__

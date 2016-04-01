This package contains an Address Book demo application, voluntarily written in Plain PHP 5, and organized in a near MVC-like structure.

Features: 
 - CRUD operations for managing contacts and groups
 - Add contacts to groups, inherit between groups
 - Export all contacts to an XML file

For a more demanding app, I would preferably use Symfony or Laravel for faster implementation, 
better security features and DB abstraction.

* DATABASE:
- Driver: pdo-mysql
- Parameters can be set in addbook/config.php
- If debugging is enabled, queries and info/error messages will be logged in addbook.log
- The DB schema can be imported from addbook/tests/addbook201603.sql
- Credentials:
   GRANT SELECT, INSERT, UPDATE, DELETE ON `addbook`.* TO 'demo'@'localhost' IDENTIFIED BY ';demo;' ;


* UI:
- jQuery 1.12 & Bootstrap 3.3 frameworks are used for a responsive layout across device sizes.
- Resources files (css, js, fonts) are stored under addbook/res/ to work offline.
- addbook/index.php serves as front controller.


* Deployment on Linux:
- Internal PHP test server:  cd addbook/ ; sudo php -S localhost:9000 &

- Apache (Ubuntu): 
    sudo cp -R addbook /var/www/html/ 
    sudo chown -R www-data.www-data /var/www/html/addbook


Author: Makhtar Diouf <makhtar.diouf@gmail.com>

2016-03

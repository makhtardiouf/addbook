
This package contains an Address Book demo application .
It is voluntarily written in Plain PHP 5, and organized in a near MVC-like structure.
For a more demanding app, I would preferably use Symfony or Laravel for faster implementation, 
better security features and DB abstraction.

* DATABASE:
- Driver: pdo-mysql
- NOTE: it is better to backup/drop the existing one if any, as the schema changed largely in this version 2.

- Parameters can be set in addbook-md/config.php
- If debugging is set to true, queries and infor/error messages will be logged in addbook.log
- The DB schema can be imported from addbook-md/tests/addbook-md201603.sql
- Credentials:
   GRANT SELECT, INSERT, UPDATE, DELETE ON `addbook-md`.* TO 'demo'@'localhost' IDENTIFIED BY ';demo;' ;


* UI:
- jQuery 1.12 & Bootstrap 3.3 frameworks are used for a responsive layout across device sizes.
- Resources files (css, js, fonts) are stored under addbook-md/res/ to work offline.
- addbook-md/index.php serves as front controller.


* Deployment on Linux:
- Internal PHP test server:  cd addbook-md/ ; sudo php -S localhost:9000 &

- Apache (Ubuntu): 
    sudo cp -R addbook-md /var/www/html/ 
    sudo chown -R www-data.www-data /var/www/html/addbook-md


Author: Makhtar Diouf <makhtar.diouf@gmail.com>

2016-03

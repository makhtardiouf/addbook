<?php
/* $Id: config.php,v 81f3011898c8 2016/03/06 19:09:43 makhtar $
 * Makhtar Diouf 
 * DB and other parameters
 */
define("DB_USER", 'demo');
define("DB_PASS", ';demo;');
define("DB_NAME", 'addbook-md');
define("DB_SERVER_ADDR", 'localhost');

define("TB_CITY", 'cities');
define("TB_GROUPS", 'groups');
define("TB_CONTACT", 'contacts');
define("TB_CT_GROUPS", 'contacts_groups');  // Join table for Contacts and Groups

define("PAGINATION_ROWS", 10);
define("DEBUG_ON", true);          // Log queries and error msg into LOG_FILE

define("LOG_FILE", "./addbook.log");
define("EXPORT_DIR", './export');  // Directory where to export xml files
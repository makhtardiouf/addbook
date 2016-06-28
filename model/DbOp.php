<?php

/* Makhtar Diouf 
 * $Id: DbOp.php,v 483fadaed043 2016/03/07 05:15:34 makhtar $
 * Database operations.
 */
require_once 'config.php';
require_once 'utils.php';

class DbOp {

    private $con;
    public $errorMsg;

    public function __construct() {
        $this->Connect();
        $this->errorMsg = '';
    }

    /**
     * Connect to the MySQL DB with a  persistent connection.
     *
     * @return PDO connection object
     */
    public function Connect() {
        try {
            if ($this->con instanceof PDO) {
                return;
            }

            $this->con = new PDO('mysql:host=' . DB_SERVER_ADDR . ';dbname=' . DB_NAME, DB_USER, DB_PASS, array(PDO::ATTR_PERSISTENT => true));

            if (!$this->con) {
                echo 'Error: could not connect to the database';
                exit();
            }
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            Logit('Connected to DB ' . DB_SERVER_ADDR);

            return $this->con;
        } catch (PDOException $e) {
            if (!class_exists('PDO')) {
                echo 'CRITICAL: please make sure that the pdo-mysql driver is installed<br>';
            }
            $err = 'DB query error: ' . $e->getMessage();
            echo $err;
            Logit($err);
            return;
        }
    }

    /**
     * Execute an SQL query.
     *
     * @param string $query
     *
     * @return associative array(default), or 'Contact' object
     */
    public function Query($query, $fetchMode = PDO::FETCH_ASSOC, $className = '') {
        try {
            if (!($this->con instanceof PDO)) {
                $this->Connect();
            }
            $ret = $this->con->query($query);
            if (!$ret) {
                return $this->con->errorInfo();
            }
            if (!empty($className)) {
                $ret->setFetchMode($fetchMode, $className);
            } else {
                $ret->setFetchMode($fetchMode);
            }

            Logit($query);
            return $ret;
        } catch (PDOException $e) {
            $err = 'DB query error: ' . $e->getMessage();
            echo $err;
            Logit($err);
            return;
        }
    }

    /**
     * Prepare and Execute a statement.
     *
     * @param $query string 
     *
     * @return type
     */
    public function PrepExecute($query, $arr = array(), $fetchMode = PDO::FETCH_ASSOC, $className = '') {
        try {
            if (!($this->con instanceof PDO)) {
                $this->Connect();
            }
            $stm = $this->con->prepare($query);

            if (!empty($className)) {
                $stm->setFetchMode(PDO::FETCH_CLASS, $className);
            } else {
                $stm->setFetchMode($fetchMode);
            }

            $stm->execute($arr);
            Logit($query);
            Logit($arr);
            return $stm;
            
        } catch (PDOException $e) {
            $err = 'DB error: ' . $e->getMessage();
            echo $err;
            $err .= PHP_EOL . print_r($query, true);
            $err .= PHP_EOL . print_r($arr, true);
            Logit($err);
            return false;
        }
    }

    /**
     * @return type PDO connection, to be used for e.g. transactions
     */
    public function GetConnection() {
        return $this->con;
    }

}

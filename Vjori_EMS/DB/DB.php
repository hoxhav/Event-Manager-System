<?php

class DB {
    /* I had the idea of having a singleton db connection so I can create it once and share it between classes
     * I got the syntax from here https://gist.github.com/skhani/5aebd11015881fb3d288 */

    private $_connection;
    private static $_instance; //The single instance

    /*
      Get an instance of the Database
      @return Instance
     */

    public static function getInstance() {
        if (!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    // Constructor
    private function __construct() {
        require_once ("dbInfo.php");
        try {
            // $this->_connection = new PDO('mysql:host=localhost;dbname=vxh5681', 'root', 'mysql');
            $this->_connection = new PDO("mysql:host={$host};dbname={$db}", $user, $pass);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    // Magic method clone is empty to prevent duplication of connection
    private function __clone() {
        
    }

    // Get mysql pdo connection
    public function getConnection() {
        return $this->_connection;
    }

}

?>
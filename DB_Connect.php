<?php
 
class DB_Connect {
 
    // constructor
    function __construct() {
         
    }
 
    // destructor
    function __destruct() {
        // $this->close();
    }
 
    // Connecting to database
    public function connect() {
        require_once 'config.php';
        // connecting to mysql
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
        // selecting database
        mysqli_select_db($con, DB_DATABASE);
 
        // return database handler
        return $con;
    }
	
	// Connecting to database
    public function connect2() {
        require_once 'config.php';
        // connecting to mysql
        $con2 = mysql_connect(DB_HOST2, DB_USER2, DB_PASSWORD2);
        // selecting database
        mysql_select_db(DB_DATABASE2);
 
        // return database handler
        return $con2;
    }
 
    // Closing database connection
    public function close() {
        mysql_close();
    }
 
}
 
?>
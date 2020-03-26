<?php

class DB_Functions {
 
    private $db;
    private $con;
    function __construct() {
		require_once 'DB_Connect.php';
        $this->db = new DB_Connect();
        $this->con = $this->db->connect();
    }

    function __destruct() {
         
    }
	
    
    public function insertInbound($userId, $content, $respond, $inbondCode) {
        $ret = mysqli_query($this->con, "INSERT INTO Inbound VALUES(uuid(), '$userId', '$content', '$respond', '$inbondCode', now())");
        return $ret;
    }
    
    public function getInbound($userId, $inbondCode) {
        $json = array();
        if($result = mysqli_query($this->con, "SELECT userId,content,respond,inboundCode FROM Inbound WHERE userid='$userId' and inboundcode='$inbondCode';"))
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $json[] = $row;
            }
        }
        return $json;
	}
	
    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {
 
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }
 
    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {
 
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
 
        return $hash;
    }
	public function test_code($code) {
 
        $hash = "112233";
 
        return $hash;
    }
	
	public function randomPrefix($length) {       
        $random = "";
        srand((double) microtime() * 1000000);
        //$data = "0123456789";
        $data = "A0B1C2DE3FG4HIJ5KLM6NOP7QR8ST9UVW0XYZ";
        for ($i = 0; $i < $length; $i++) {
            $random .= substr($data, (rand() % (strlen($data))), 1);
        }

        return $random;
    }
 
}
 
?>
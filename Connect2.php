<?php
class Connect {

    public $conn;
    public $output;
 
    // Connecting to database
    public function __construct() {
         
        // Connecting to mysql database
        $this->conn = new mysqli('3.36.89.192', "user", "useruser", "DBProject");

        $output['success']=array('state' => true, 'reason' => 'None');
        
        if (!$this->conn) {

            $output['success']=array(
                'state' => false,
                'reason' => 'Error: Unable to connect to MySQL.',
                'detail' => array('errno' => mysqli_connect_errno(), "error" => mysqli_connect_error()));

            exit;

	    } else{
            $output['success']=array( 'state' => true, 'reason' => 'None');
            // return database handler
        }

        $this->output = $output;
    }

    public function getsql(){ return $this->conn; }


    
}

$db = new Connect();
$output = $db->output;
//echo(json_encode($output['success']));

    
?>
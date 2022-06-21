<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

abstract class DB{
    
    protected  $conn;
    protected  $affectedRows =0;
    protected string $db_type = 'mysqli';

    abstract protected  function setAffectedRows();
     function __construct( 
        protected string $db_host,
        protected string $db_user,
        protected string $db_passw,
        protected string $db
    ) {
              if($this->db_type !=="mysqli"){
                die("This system was builded to use with MySQLi");
              }

             if($db_host!==""  && $db_user!=="") {
               
                $rs = $this->connect($db_host, $db_user, $db_passw, $db);
        
             }
      }

    public function connect($host, $user, $pass, $db){
        try{
        $this->conn = new mysqli($host, $user, $pass, $db);
        if(mysqli_connect_errno()){
            throw new Exception('Could not connect to database ' .$db);
        }
        } catch(Exception $e){
            throw new Exception ($e->getMessage());
        }
    }



    public function setServerHost($host) : string {
        $this->db_host = $host;
    }

    public function setDBUser($user) : string {
        $this->db_user = $user;
    }

    public function setDBPass($pass) : string {
        $this->db_passw = $pass;
    }

    public function get_connection() : object{
        return $this->conn;
    }

    public function  get_affectedRows() : int|string{
        return $this->affectedRows;
    }

    public function close(){
        return mysqli_close($this->conn);
        unset($this->conn);
    }
}



class DB_Operations extends DB{

    protected $_result;
    protected $_sql;
    protected $_numRows;
    function __construct($server , $user, $passw , $db){
       try{
          parent::__construct($server, $user, $passw, $db);
       }catch(Excetion $e){
        throw new Exception($e->getMessage()); 
       } 
      
    }


    protected function setAffectedRows(){
        return  mysqli_affected_rows($this->conn);
    }


    public function select($sql, $a_param_type, $a_bind_params) :array {
        $arr = [];
        try{
     
            $a_params = array();
 
            $param_type = '';
            $n = count($a_param_type);
            for($i = 0; $i < $n; $i++) {
                    $param_type .= $a_param_type[$i];
            }

            $a_params[] = & $param_type;
   
            for($i = 0; $i < $n; $i++) {
             
                $a_params[] = & $a_bind_params[$i];
            }
            $stmt = $this->conn->prepare($sql);
            if($stmt === false) {
                    trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $this->conn->errno . ' ' . $this->conn->error, E_USER_ERROR);
            }
            

            call_user_func_array(array($stmt, 'bind_param'), $a_params);
 

        $stmt->execute();
        $result = $stmt->get_result();
        $ret_data =  [];

        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            array_push($ret_data, $row);
          }
       // $stmt->free_result();
       
        }catch(Exception $e){
            throw new Exception ($e->getMessage());
        }

        return $ret_data;
    }

 

}


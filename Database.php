<?php

class Database{
    public $servername;
    public $username;
    public $password;
    public $dbname;
    public $conn;
    public static $instance;

    private function __construct($sn , $us , $pw , $dbn){
        $this->servername = $sn;
        $this->username = $us;
        $this->password = $pw;
        $this->dbname = $dbn;
        if(self::$instance == NULL){
            self::$instance = $this;
            $this->startConnection();
        }
    }

    function startConnection(){
        if($this->conn == NULL){
            $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        }

        if(!$this->conn){
            //echo "Database Connection Failed";
        }
        else{
            //echo "Database Connection Succeeded";
        }
    }

    public static function getInstance(){
        if(self::$instance == NULL){
            $db = new Database("localhost", "root", "", "task5");
        }
        return self::$instance;
    }

    function insert($tableName , $fieldsName , $data){
        $fields = "";
        $values = "";
        for($i = 0 ; $i < count($fieldsName) ; $i++){
            $fields = $fields.$fieldsName[$i].",";
            $values = $values."'".$data[$i]."',";
        }

        $fields = substr($fields , 0 , strlen($fields) - 1);
        $values = substr($values , 0 , strlen($values) - 1);

        $sql = "insert into ".$tableName." (".$fields.") values (".$values.") "; 
        //echo "Sql = ".$sql;
        
        if(mysqli_query($this->conn, $sql)){
            //echo "Insertion Succeeded";
            return true;
        }
        else{
            //echo "Insertion Failed";
            return false;
        }
    }

    function selectWhere($tableName , $condition)
    {
            $sql = "select * from ".$tableName." where ".$condition."";
            $result = mysqli_query($this->conn, $sql);
            if(mysqli_num_rows($result) > 0)
            {
               return true;
               echo"there is already a username with that name";
            }
            else
            {   
                return false;
                echo "You are a new User!!!";
            }
                
                

    }

    function CheckLogin($tableName , $condition)
    {
            $sql = "select * from ".$tableName." where ".$condition."";
            $result = mysqli_query($this->conn, $sql);

            if(mysqli_num_rows($result) > 0)
            {
                return $result;
            }
            else
            {
                echo "Wrong info please try again";
            }
    }


    function closeConnection(){
        $this->conn->close();
    }
}
?>
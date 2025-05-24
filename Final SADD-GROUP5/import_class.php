<?php

define("DATABASE", "attendance");


class DataImport {
    private $server = "mysql:host=localhost;dbname=".DATABASE;
    private $user = "root";
    private $password = "";
    private $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
    protected $con;

    public function connect(){
        try {
            $this->con = new PDO($this->server, $this->user, $this->password, $this->options);
            return $this->con;
        } catch (PDOException $e) {
            echo "There is some problem in the connection : ".$e->getMessage();
        }
    }

    public function insert_data($array)
    {
        $first_name = trim($array[0]);
        $last_name = trim($array[1]);
        $email = trim($array[2]);

        try {
            $connection = $this->connect();
            $stmt = $connection->prepare("INSERT INTO users (`first_name`,`last_name`,`email`) VALUE (?,?,?)");
            $stmt->execute([$first_name, $last_name, $email]);
            //$id = $connection->lastInsertId();
            return "success";
        } catch (PDOException $e) {
            echo "There is some problem in inserting data : ".$e->getMessage();
        }
    }
}
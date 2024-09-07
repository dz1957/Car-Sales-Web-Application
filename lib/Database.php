<?php

include "config/config.php";


// Class Databse

class  Database{

  public $mysql;


  // Construct Class
  public function __construct(){

    if (!isset($this->mysql)) {
      try {
        $link = new mysqli(DB_HOST,  DB_USER,  DB_PASS,  DB_NAME);
        // $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // $link->exec("SET CHARACTER SET utf8");
        $this->mysql  =  $link;
      } catch(Exception $e) {
        die("Connection error...".$link->connect_error);  # what is connect_error?
      }

    }


  }








}

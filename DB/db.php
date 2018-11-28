<?php
class dbh {
  private $host = "localhost";
  private $user = "root";
  private $pass = "Mikeyg007";
  private $databaseName = "ABET";
  //private $tableName = "t";

  //--------------------------------------------------------------------------
  // 1) Connect to mysql database
  //--------------------------------------------------------------------------
  protected function connect()
  {
	
	$con = new mysqli($this->host, $this->user, $this->pass, $this->databaseName);  
	return $con;
  } 

}
?>
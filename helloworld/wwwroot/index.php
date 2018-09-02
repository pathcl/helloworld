<?php

require_once 'dbconfig.php';

$dsn = "pgsql:host=$host;port=5432;dbname=$db;user=$username;password=$password";

try{
 // create a PostgreSQL database connection
 $conn = new PDO($dsn);

 // display a message if connected to the PostgreSQL successfully
 if($conn){
 echo "Connected to the <strong>$db</strong> database successfully!\n";
 echo "";

 // retrieve data

 $sql = "select * from employees";
 $statement = $conn->query($sql);
 $row = $statement->fetch(PDO::FETCH_ASSOC);

 echo $row["name"] . " " . $row["lastname"] . "\n";

 }
}catch (PDOException $e){
 // report error message
 echo $e->getMessage();
}

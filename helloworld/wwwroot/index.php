<?php

require_once 'dbconfig.php';

$dsn = "pgsql:host=$host;port=5432;dbname=$db;user=$username;password=$password";

try{
 // create a PostgreSQL database connection
 $conn = new PDO($dsn);

 // display a message if connected to the PostgreSQL successfully
 if($conn){
 echo "Connected to the <strong>$db</strong> database successfully!";
 echo "\n";

 // retrieve data

 $sql = "select * from employees";
 $statement = $conn->query($sql);
 $row = $statement->fetch(PDO::FETCH_ASSOC);

 // echo $row["name"] . " " . $row["lastname"] . "\n";

 }
}catch (PDOException $e){
 // report error message
 echo $e->getMessage();
}


 $redisObj = new Redis();

  function openRedisConnection( $hostName, $port){
  	global $redisObj;
	// Opening a redis connection
  	$redisObj->connect( $hostName, $port );
  	return $redisObj;
  }

  function setValueWithTtl( $key, $value, $ttl ){

  	try{
  		global $redisObj;
		// setting the value in redis
  		$redisObj->setex( $key, $ttl, $value );
  	}catch( Exception $e ){
  		echo $e->getMessage();
  	}
  }

  function getValueFromKey( $key ){
  	try{
  		global $redisObj;
		// getting the value from redis
  		return $redisObj->get( $key);
  	}catch( Exception $e ){
  		echo $e->getMessage();
  	}
  }

  function deleteValueFromKey( $key ){
  	try{
  		global $redisObj;
		// deleting the value from redis
  		$redisObj->del( $key);
  	}catch( Exception $e ){
  		echo $e->getMessage();
  	}
  }


  // Making json string
  $jsonValue = json_encode($row);

  // Opening a redis connection
  openRedisConnection( 'redis.default.svc.cluster.local', 6379 );

  // Inserting the value with ttl =  1 hours
  setValueWithTtl( 'somekey1', $jsonValue, 3600);

  // Fetching value from redis using the key.
  $val = getValueFromKey( 'somekey1' );

  //  Output:  the json encoded array from redis
  echo "<strong> output from redis</strong>";
  echo "\n";
  echo $val;

  // Unsetting value from redis
  deleteValueFromKey( $key );
?>
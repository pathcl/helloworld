<?php 
  /* Functions for redis operations starts here   */ 

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

   /* Functions for converting sql result  object to array goes below  */ 

  function convertToArray( $result ){ 
  	$resultArray = array(); 

  	for( $count=0; $row = $result->fetch_assoc(); $count++ ) { 
  		$resultArray[$count] = $row; 
  	} 

  	return $resultArray; 
  } 

   /* Functions for executing the mySql query goes below   */ 

  function executeQuery( $query ){ 
     $mysqli = new mysqli( 'localhost',  'username',  'password',  'someDatabase' ); 

     if( $mysqli->connect_errno ){ 
       echo "Failed to connect to MySql:"."(".mysqli_connect_error().")".mysqli_connect_errno(); 
     } 

     $result =  $mysqli->query( $query ); 
	 // Calling function to convert result  to array
     $arrResult = convertToArray( $result );

     return $arrResult;   	 
  }  

  $query = 'select * from sometable limit 1'; 
  // Calling function to execute sql query
  $arrValues = executeQuery( $query );

  // Making json string
  $jsonValue = json_encode($arrValues);

  // Opening a redis connection
  openRedisConnection( 'localhost', 6379 );

  // Inserting the value with ttl =  1 hours
  setValueWithTtl( 'somekey1', $jsonValue, 3600);

  // Fetching value from redis using the key. 
  $val = getValueFromKey( 'somekey1' ); 

  //  Output:  the json encoded array from redis 
  echo $val;

  // Unsetting value from redis
  deleteValueFromKey( $key );

?>
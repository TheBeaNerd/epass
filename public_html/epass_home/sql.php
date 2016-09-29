<?php
function getSavedSettings() {
  global $INPUT_url, $INPUT_user, $INPUT_password, $SQL_upper,$SQL_lower,$SQL_number,$SQL_special,$SQL_size,$SQL_version;  
  $SQL_version = 1;
  $sql = openSQL();
  $ID = upass($INPUT_user,$INPUT_password);
  $query = "SELECT * FROM USERS WHERE ID='$ID' AND URL='$INPUT_url'";
  $result = mysqli_query($sql, $query);
  if ($result !== false) {
    while($row = mysqli_fetch_array($result)) {
	  	  $SQL_upper = $row['UPPER'];
		  $SQL_lower = $row['LOWER'];
		  $SQL_number = $row['NUMBER']; 
	          $SQL_special = $row['SPECIAL'];
		  $SQL_size = $row['SIZE'];  
		  $SQL_version = $row['VERSION'];
    }
  }
  mysqli_close($sql);
}

function getDefaultSettings() {
    global $SQL_upper, $SQL_lower, $SQL_number, $SQL_special, $SQL_size, $SQL_version;
    $SQL_upper   = 1;
    $SQL_lower   = 1;
    $SQL_number  = 1;
    $SQL_special = 0;
    $SQL_size    = 10;
    $SQL_version = 1;
    getSavedSettings();
}

function saveSettings($change) {
  global $INPUT_url, $INPUT_user, $INPUT_password, $SQL_upper,$SQL_lower,$SQL_number,$SQL_special,$SQL_size,$SQL_version;  
  $SQL_version = $SQL_version + $change;
  $sql = openSQL();
  $ID = upass($INPUT_user,$INPUT_password);
  $query = "REPLACE INTO USERS(ID, USERNAME, URL, UPPER, LOWER, SPECIAL, NUMBER, SIZE, VERSION) VALUES ('$ID', '$INPUT_user', '$INPUT_url', '$SQL_upper', '$SQL_lower', '$SQL_special', '$SQL_number', $SQL_size, $SQL_version)";
  $result = mysqli_query($sql, $query);
  if (result === false) {
    echo "<PRE>SQL: set changes failed</PRE>";
  }
  mysqli_close($sql);
  getSavedSettings();
}

function openSQL () {
  global $DBASE_user,$DBASE_pass,$DBASE_name;
//  echo("<P>Connecting to MySQL</P>"); $DBASE_user
  $sql = mysqli_connect('localhost',$DBASE_user,$DBASE_pass,$DBASE_name);
  if (mysqli_connect_errno($sql)) {
    echo("<P>Failed to connect to MySQL : " . mysqli_connect_error() . "</P>\n");   
  }
  $query = "SELECT ID FROM USERS";
  $result = mysqli_query($sql, $query);
  if($result === false) {
    $query = "CREATE TABLE USERS (
                ID varchar(128) NOT NULL,
                USERNAME varchar(128) NOT NULL,
                URL varchar(128) NOT NULL,
                UPPER int,
                LOWER int,
                SPECIAL int,
                NUMBER int,
                SIZE int,
                VERSION int,
	        PRIMARY KEY  (ID, URL))";
    $result = mysqli_query($sql, $query);
    if ($result === false) {
      echo "<PRE>SQL: failed to create entry : ".mysqli_error($sql). "</PRE>";
    }
  } 
  return $sql;
}
?>
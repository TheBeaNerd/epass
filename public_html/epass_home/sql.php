<?php
function getChangedSettings() {
  global $url, $user, $password, $upper,$lower,$number,$special,$size,$version;  
  $version = 1;
  $sql = openSQL();
  $ID = upass($user,$password);
  $query = "SELECT * FROM USERS WHERE ID='$ID' AND URL='$url'";
  $result = mysqli_query($sql, $query);
  if ($result !== false) {
    while($row = mysqli_fetch_array($result)) {
	  	  $upper = $row['UPPER'];
		  $lower = $row['LOWER'];
		  $number = $row['NUMBER']; 
	          $special = $row['SPECIAL'];
		  $size = $row['SIZE'];  
		  $version = $row['VERSION'];
    }
  }
  mysqli_close($sql);
}

function setDefaultSettings() {
    global $upper, $lower, $number, $special, $size;
    $upper   = 'checked';
    $lower   = 'checked';
    $number  = 'checked';
    $special = '';
    $size    = 10;
    getChangedSettings();
}

function setChanges($change) {
  global $url, $user, $password, $upper,$lower,$number,$special,$size,$version;  
  $version = $version + $change;
  $sql = openSQL();
  $ID = upass($user,$password);
  $query = "REPLACE INTO USERS(ID, USERNAME, URL, UPPER, LOWER, SPECIAL, NUMBER, SIZE, VERSION) VALUES ('$ID', '$user', '$url', '$upper', '$lower', '$special', '$number', $size, $version)";
  $result = mysqli_query($sql, $query);
  if (result === false) {
    echo "<PRE>SQL: set changes failed</PRE>";
  }
  mysqli_close($sql);
  getChangedSettings();
}

function openSQL () {     
  global $url, $user, $password, $upper,$lower,$number,$special,$size,$dbase_user,$dbase_pass,$dbase,$version;        
//  echo("<P>Connecting to MySQL</P>"); $dbase_user
  $sql = mysqli_connect('localhost',$dbase_user,$dbase_pass,$dbase);
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
                UPPER char(10),
                LOWER char(10),
                SPECIAL char(10),
                NUMBER char(10),
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
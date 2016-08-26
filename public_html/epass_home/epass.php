 <?php
if (FALSE == @include __DIR__ . '/../../sql.php') {
  exit;
}
$generateButton    = $_POST['generateButton'];
$manualButton      = $_POST['manualButton'];
$passwordButton    = $_POST['passwordButton'];
$settingsButton    = $_POST['settingsButton'];
$createButton      = $_POST['createButton'];
$changeButton      = $_POST['changeButton'];
$versionButton     = $_POST['versionButton'];
$postUpper    = $_POST['upper'];
$postLower    = $_POST['lower'];
$postNumber   = $_POST['number'];
$postSpecial  = $_POST['special'];
$postSize     = $_POST['size'];
$url      = sanitizeURL($_POST['url']);
$user     = sanitizeUser($_POST['user']);
$password = $_POST['password'];

function epass() {
  // This is the top level epass() function.
  global $generateButton, $manualButton, $passwordButton, $settingsButton, $createButton, $changeButton, $versionButton;
  if (isset($manualButton)) {
    generateManualPage();
  } elseif ((isset($settingsButton))&&(notNull())) {
    generateSettings();
  } elseif (isset($createButton)) {
    createPassword();
  } elseif ((isset($changeButton))&&(notNull())) {
    changePassword(); 
  } elseif ((isset($passwordButton))&&(notNull())) {
    generatePassword();
  } elseif (isset($versionButton)) {
    changeVersion();
  } else {
    // Default Behavior
    generateSplashPage();
  }
}

function generateManualPage() {
  // Here is an example of loading a raw HTML file directly ..
  global $epass_home;
  include $epass_home . "manual.html";
}

function generateSplashPage() {
  global $epass_home;
  include $epass_home . "index.html";
}

function generatePassword() {
    global $upper, $lower, $number, $special, $size, $url, $password, $user;
    $res = openHTML();
    $res .= passInfo();
    setDefaultSettings();
    $res .= hR(); 
    $res .= postHash('Copy and Paste');
    $res .= hR();
    $res .= closeHTML();
    echo($res);
  }
  
function generateSettings() {
    global $upper, $lower, $number, $special, $size, $url, $password, $user;
    $res = openHTML();
    $res .= passInfo();
    setDefaultSettings();
    $res .= characterSelection();
    $res .= requestHash(); 
    $res .= hR();
    $res .= closeHTML();
    echo($res);
}
      
function createPassword() {
  global $upper, $lower, $number, $special, $size, $url, $password, $user;
  $res = openHTML();
  $res .= passInfo();
  getChangedSettings();
  getPostData();
  setChanges(0);
  $res .= hR();    
  $res .= postHash('Copy and Paste');
  $res .= hR();
  $res .= closeHTML();
  echo($res);
  }
  
function changePassword() {
  global $upper, $lower, $number, $special, $size, $url, $password, $user;
  $res = openHTML();
  $res .= passInfo();
  setDefaultSettings();
  $res .= hR();    
  $res .= postHash('Old Password');
  $res .= hR();
  setDefaultSettings();
  setChanges(1);  
  $res .= postHash('New Password');
  $res .= hR();
  $res .= closeHTML();
  echo($res);
  }

function changeVersion() {
  global $upper, $lower, $number, $special, $size, $url, $password, $user;
  $res = openHTML();
  $res .= passInfo();
  setDefaultSettings();
  $res .= hR();    
  $res .= postHash('Old Password');
  $res .= hR();
  getPostData();
  setChanges(1);  
  $res .= postHash('New Password');
  $res .= hR();
  $res .= closeHTML();
  echo($res);
}


// SQL code


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

// Hash code bits

function upass($user,$password) {
  $hash = bcrypt($user . $password);
  $hash = mb_strimwidth($hash,0,128);
  return($hash);
}

function selectAllowable($upper,$lower,$number,$special) {
  $res = array();
  $index = 0;
  if (! ($upper || $lower || $number || $special)) {
    // Yay! Binary!
    $res[0]=chr(0x30);
    $res[1]=chr(0x31);
  }
  if ($special) {
    for ($i=0x20;$i<=0x2F;$i++) {
      $res[$index++]=chr($i);
    }
  }
  if ($number) {
    for ($i=0x30;$i<=0x39;$i++) {
      $res[$index++]=chr($i);
    }
  }
  if ($special) {
    for ($i=0x3A;$i<=0x40;$i++) {
      $res[$index++]=chr($i);
    }
  }
  if ($upper) {
    for ($i=0x41;$i<=0x5A;$i++) {
      $res[$index++]=chr($i);
    }
  }
  if ($special) {
    for ($i=0x5B;$i<=0x60;$i++) {
      $res[$index++]=chr($i);
    }
  }
  if ($lower) {
    for ($i=0x61;$i<=0x7A;$i++) {
      $res[$index++]=chr($i);
    }
  }
  if ($special) {
    for ($i=0x7B;$i<=0x7E;$i++) {
      $res[$index++]=chr($i);
    }
  }
  return($res);
}

function iterationCount($str) {
  $strlen = strlen( $str );
  $value = 0;
  for( $i = 0; $i < $strlen; $i++ ) {
    $char = substr($str,$i,1);
    $value = (ord($char)*127 + $value) % 32771;
  }
  //return 10 + ($value % 8);
  return 327680 + $value;
}

function salt($str) {
  global $hash_salt;
  $salt = $hash_salt . '$';
  $cnfg = '$6$rounds=' . iterationCount($str) . '$';
  return($cnfg . $salt);
}

function bcrypt($input) {
  $salt = salt($input);
  $size = strlen($salt);
  $z = crypt($input,$salt);
  //echo "<PRE>" . $z . "</PRE>";
  //$val = str_replace($salt,"",$z);
  $val = substr($z,$size);
  return($val);
}

function hashBits($char) {
  // The crypt() function returns characters in the set [a-zA-Z0-9/.]
  // This function maps those characters into values between 0 and 63
  // Which, it turns out, is 5 bits.
  $ascii = ord($char);
  $bits = 0;
  if ($ascii == 0x2E) return($bits);
  $bits++;
  if ($ascii == 0x2F) return($bits);
  $bits++;
  if (($ascii >= 0x41) && ($ascii <= 0x5A)) return($ascii-0x41+$bits);
  $bits+=26;
  if (($ascii >= 0x61) && ($ascii <= 0x7A)) return($ascii-0x61+$bits);
  $bits+=26;
  if (($ascii >= 0x30) && ($ascii <= 0x39)) return($ascii-0x30+$bits);
  return(0);
}

function encodeBits($bits) {
  if ($bits == 0) return(chr(0x2E));
  $bits -= 1;
  if ($bits == 0) return(chr(0x2F));
  $bits -= 1;
  if ($bits < 26) return(chr(0x41+$bits));
  $bits -= 26;
  if ($bits < 26) return(chr(0x61+$bits));
  $bits -= 26;
  return(chr(0x30+$bits));
}

function encodeArray($arr) {
  $str = "";
  for ($i=0;$i<count($arr);$i++) {
    $str .= encodeBits($arr[$i]);
  }
  return $str;
}

function decodeString($str) {
  // Converts a hash string (alphabet [a-zA-Z0-9/.]) into an array
  // of 5-bit integer values (0..63)
  $str = str_split($str);
  $arr = array();
  for ($i=0;$i<count($str);$i++) {
    $arr[$i] = hashBits($str[$i]);
  }
  return $arr;
}

function nextDigit($base,$max,$min,$index,$arr) {
  $hi = $base*$max;
  $lo = $base*$min;
  $hin = floor($hi);
  $lon = floor($lo);
  if ($hin == $lon) {
    $max = $hi - $hin;
    $min = $lo - $lon;
    return(array($hin,$max,$min,$index));
  }
  $err = ($max - $min)/64.0;
  // echo "<PRE>" . $index . "</PRE>";
  $cof = hashBits($arr[$index++]);
  $max = $min + ($cof+1)*$err;
  $min = $min + $cof*$err;
  return(nextDigit($base,$max,$min,$index,$arr));
}

function remapHash($base,$str) {
  $arr = str_split($str);
  $res = array();
  $bits  = hashBits($arr[0]);
  $max   = ($bits+1)/64.0;
  $min   = $bits/64.0;
  $index = 1;
  for ($i=0;$i<16;$i++) {
    list($r,$max,$min,$index) = nextDigit($base,$max,$min,$index,$arr);
    $res[$i] = $r;
  }
  return($res);
}

function unMap($umapsize,$arr,$map) {
  $res = "";
  for ($i=0;$i<$umapsize;$i++) {
    $res .= $map[$arr[$i]];
  }  
  return($res);
}

function passwordSpec($upper,$lower,$number,$special,$size,$entropy) {
  //
  // Invariant : $size >= $upper + $lower + $number + $special
  //
  // A password spec is an array of character arrays.  The size of
  // the spec is the same as the subsequent password.  Each array
  // in the spec lists the characters allowed in that position.
  //
  $spec = array();
  $index = array();
  $map = selectAllowable($upper,$lower,$number,$special);
  for ($i=0;$i<$size;$i++) {
    // Fill the spec with the default maps
    $spec[$i] = $map;
    $index[$i] = $i;
  }

  $count = $upper;
  $map = selectAllowable($count,0,0,0);
  for ($x=0;$x<$count;$x++) {
    $loc = $entropy->chooseIndex($size);
    $spec[$index[$loc]] = $map;
    unset($index[$loc]);
    $size--;
  }

  $count = $lower;
  $map = selectAllowable(0,$count,0,0);
  for ($x=0;$x<$count;$x++) {
    $loc = $entropy->chooseIndex($size);
    $spec[$index[$loc]] = $map;
    unset($index[$loc]);
    $size--;
  }

  $count = $number;
  $map = selectAllowable(0,0,$count,0);
  for ($x=0;$x<$count;$x++) {
    $loc = $entropy->chooseIndex($size);
    $spec[$index[$loc]] = $map;
    unset($index[$loc]);
    $size--;
  }
  
  $count = $special;
  $map = selectAllowable(0,0,0,$count);
  for ($x=0;$x<$count;$x++) {
    $loc = $entropy->chooseIndex($size);
    $spec[$index[$loc]] = $map;
    unset($index[$loc]);
    $size--;
  }

  return $spec;
}

class Entropy {

  var $arr;
  var $index;
  var $err;
  var $min;

  function Entropy($hash) {
    
    $this->arr   = decodeString($hash);
    $this->index = 0;
    // We maintain the invariant that err and min are always in the
    // range (0..1)
    $this->err   = 1.0;
    $this->min   = 0.0;
    // Technically I think our invariant is (min + err <= 1.0)
  }

  function pentaBit() {
    // Return a "random" value in the rnage (0..63).
    $curr_index = $this->index;
    $curr_value = $this->arr[$curr_index];
    $curr_value = $curr_value % 64;
    $next_index = ($curr_index + 1) % count($this->arr);
    $next_value = $this->arr[$next_index];
    $next_value = ($curr_value + $next_value) % 64;
    $this->arr[$next_index] = $next_value;
    $this->index = $next_index;
    return $curr_value;
  }

  function chooseIndex($base) {
    while(true) {
      //
      // Scale our min and error values (0..1) into the current base
      // (0..B) (ie: the size of the value array).
      //
      // lo is the lower bound of our estimate.
      $lo  = $base*$this->min;
      // off is our error term
      $off = $base*$this->err;
      // The upper bound of our estimate (lo + off)
      $hi = $lo + $off;
      // z is the integer partition containing lo ..
      $z = floor($lo);
      if ($z == floor($hi)) {
        // If the low and hi are both contained within the same
        // integer partition, return the value of that partition and
        // scale err and min to reflect their relative values within
        // that partition.  Note that min and err should still satisfy
        // our invariant that they are in the range (0..1) as well as
        // the stronger invariant that min + err <= 1.0.
        $this->err = $off;
        $this->min = $lo - $z;
        return $z;
      }
      // The values lo and hi spanned multiple partitions .. we need
      // to choose a point between lo and hi and try again.  We do
      // this by slicing the error term by some fraction and adding it
      // to lo.  In doing so, we also reduce the magnitude of our
      // error estimate.  As we do this our error terms gets smaller,
      // ensuring termination.
      $this->min += ($this->pentaBit()/64.0)*$this->err;
      $this->err /= 64.0;
    }
  }

  function chooseValue($values) {
    $index = $this->chooseIndex(count($values));
    return $values[$index];
  }

  function randomString($spec) {
    // spec is an array of character arrays.
    $res = "";
    for ($i=0;$i<count($spec);$i++) {
      $res .= $this->chooseValue($spec[$i]);
    }
    return $res;
  }

}

function toNumber($selection) {
  // For now we use this to convert 'selected' to 1
  // and '' to 0.  Eventually we want to use numbers
  // for these values.
  if ($selection) {
    return 1;
  }
  return 0;
}

function doHash($upper,$lower,$number,$special,$hashsize,$str) {
  $upper   = toNumber($upper);
  $lower   = toNumber($lower);
  $number  = toNumber($number);
  $special = toNumber($special);
  $hash = bcrypt($str);
  $entropy = new Entropy($hash);
  $spec = passwordSpec($upper,$lower,$number,$special,$hashsize,$entropy);
  //$map  = selectAllowable($upper,$lower,$number,$special);
  //$base = count($map);
  //echo "<PRE> hash : " . $hash . "</PRE>";
  //$arr  = decodeString($hash);
  //$xxx  = encodeArray($arr);
  //echo "<PRE> xxxx : " . $xxx . "</PRE>";
  //$raw  = remapHash($base,$hash);  
  //$res  = unMap($hashsize,$raw,$map);
  $res = $entropy->randomString($spec);
  return(htmlentities($res,ENT_QUOTES));
}

function postHash($words) {
  global $user,$url,$password,$upper,$lower,$number,$special,$size,$version;
  $str = $user . $url . $password. $version;
  $val = doHash($upper,$lower,$number,$special,$size,$str);
  return("<center>
            <pre>$words <input $style type='text' readonly='readonly' value='$val'></pre>
          </center>");
}



// html code


function openHTML() {
  $res = '<html><head><title>Zed?</title><style type="text/css"></style></head><body text="#000000" bgcolor="#ffdead" link="#0000ee" vlink="#551a8b" alink="#ff0000"><form name="splash" method="post" action="epass.php">';
  return($res);
  }
  
function closeHTML() {
    $res = '</html></body></form>';
  return($res);
  }

function requestHash() {
  return('<td align="center" colspan="3"><input name="createButton" title="sets the default settings for the website and generates the new  password" type="submit"   
value="Generate Password"/></td><td align="center" colspan="3"><input name="versionButton" title="Changes the default settings for the website and generates the new password and old password" type="submit"   
value="Change Password"/></td>');                                                   
}

function passInfo() {
  global $url, $user, $password;
  return("<center>   
    <pre>         Website: <input $style type='text'     readonly='readonly' name = url      value='$url'></pre>
    <pre>       User Name: <input $style type='text'     readonly='readonly' name = user     value='$user'></pre>
    <pre> Master Password: <input $style type='password' readonly='readonly' name = password value='$password'></pre>
    </center>");
} 

function hR() {
  return('<br><hr width="100%">');
}

function characterSelection() {
  global $upper, $lower, $number, $special, $size;
  $upperC   = numberSelect("upper",$upper);
  $lowerC   = numberSelect("lower",$lower);
  $numberC  = numberSelect("number",$number); 
  $specialC = numberSelect("special",$special);
  $sizeSelect = optionSelect("size",8,16,$size);
  $res = 
  "<center>
   <TABLE BORDER='3' CELLSPACING='1' CELLPADDING='1'><CAPTION><pre> Character Selection </pre></CAPTION>
   <TR>
     <TD ALIGN = 'center'><pre> Number of Characters </pre></TD>
     <TD ALIGN = 'center'><pre> Upper Case </pre></TD>
     <TD ALIGN = 'center'><pre> Lower Case </pre></TD>
     <TD ALIGN = 'center'><pre> Digits </pre></TD>
     <TD ALIGN = 'center'><pre> Special Characters </pre></TD>
    </TR>
   <TR>
     <TD ALIGN=  'center'><pre>8-16</pre> $sizeSelect  </TD>
     <TD ALIGN = 'center'><pre>A-Z</pre> $upperC   </TD>
     <TD ALIGN = 'center'><pre>a-z</pre> $lowerC   </TD>
     <TD ALIGN = 'center'><pre>0-9</pre> $numberC </TD>
     <TD ALIGN = 'center'><pre>".htmlentities("!@#$%^&*,",ENT_QUOTES)."etc</pre> $specialC </TD>
    </TR>
   </TABLE>
   <p></p>";
  $res .= "";
  return($res);
}



// other code

function getPostData() {
    global $upper, $lower, $number, $special, $size, $postUpper, $postLower, $postNumber, $postSpecial, $postSize;
    $upper = $postUpper;
    $lower = $postLower;
    $number = $postNumber;
    $special = $postSpecial;
    $size = $postSize;
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

function sanitizeUser($user) {
  $pattern = '/[^a-zA-Z0-9_-]/u';
  $cleared = preg_replace($pattern, '', (string) $user);
  $lower = strtolower($cleared);
  $shorter = mb_strimwidth($lower,0,128);
  return($shorter);
}

function sanitizeURL($url) {
  $LCurl = strtolower($url);
  preg_match('%(?:http[s]?://|ftp://|file://)?(?:www.)?([a-zA-Z0-9\_\-\.]+)%', $LCurl, $result );
  $x = "thegreves.com";
  if (sizeof($result) > 1) {
    $x = $result[1];
  }
  $entries = explode(".",$x);
  $domainsize = sizeof($entries);
  if ($domainsize > 1) {
    $url = $entries[$domainsize - 2] . "." . $entries[$domainsize - 1];
  } else {
    $url =  $entries[0] . ".com";
  }
  $url = mb_strimwidth($url,0,128);
  return $url ;
}

function numberSelect($name,$value) {
if 
($value=='checked') {
$selectone = "";
$selecttwo = "selected";
} else {
$selectone = "selected";
$selecttwo = "";
}
$res =  "<select name='$name'> 
  		<option $selectone value=''>none</option>
  		<option $selecttwo value='checked'>1+</option>
  	       </select>";  	      
  	       return $res;
}

function optionSelect($name,$min,$max,$value) {
  $opt=array_fill(0,$max-$min,"");
  $opt[$value-$min]=' selected="selected" ';
  $res="<select name='$name'>";
  $index = 0;
  for ($i=$min;$i<=$max;$i++) {
    $res .= "<option value='$i'  ".$opt[$index++].">$i</option>";
  }
  $res .= "</select>";
  return($res);
}

function isChecked($value) {
  if (!strcmp($value,'checked')) return('checked="checked"');       
  return('');
}

function notNull() {
  global $password,$user,$url;
  if (($password == '')||($user == '')||($url == '')) {
     return(False);
  } else {
     return(True);
  }
}
  
  
  

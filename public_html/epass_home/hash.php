<?php
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
  global $HASH_salt;
  $salt = $HASH_salt . '$';
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
  $size = max($size,$upper + $lower + $number + $special);
  for ($i=0;$i<$size;$i++) {
    // Fill the spec with the default maps
    $spec[$i] = $map;
    $index[$i] = $i;
  }

  $count = $upper;
  $map = selectAllowable($count,0,0,0);
  for ($x=0;$x<$count;$x++) {
    $loc = $entropy->chooseIndex($size);
//    echo "<PRE>upper :" . $index[$loc] . "</PRE>";
    $spec[$index[$loc]] = $map;
//    array_splice($index,$loc,1);
    unset($index[$loc]);
    $size--;
  }

  $count = $lower;
  $map = selectAllowable(0,$count,0,0);
  for ($x=0;$x<$count;$x++) {
    $loc = $entropy->chooseIndex($size);
//    echo "<PRE>lower :" . $index[$loc] . "</PRE>";
    $spec[$index[$loc]] = $map;
//    array_splice($index,$loc,1);
    unset($index[$loc]);
    $size--;
  }

  $count = $number;
  $map = selectAllowable(0,0,$count,0);
  for ($x=0;$x<$count;$x++) {
    $loc = $entropy->chooseIndex($size);
//    echo "<PRE>number :" . $index[$loc] . "</PRE>";
    $spec[$index[$loc]] = $map;
//    array_splice($index,$loc,1);
    unset($index[$loc]);
    $size--;
  }
  
  $count = $special;
  $map = selectAllowable(0,0,0,$count);
  for ($x=0;$x<$count;$x++) {
    $loc = $entropy->chooseIndex($size);
//  echo "<PRE>special :" . $index[$loc] . "</PRE>";
    $spec[$index[$loc]] = $map;
//    array_splice($index,$loc,1);
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

function doHash($upper,$lower,$number,$special,$hashsize,$str) {
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
?>

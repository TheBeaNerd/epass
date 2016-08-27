<?php
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
?>
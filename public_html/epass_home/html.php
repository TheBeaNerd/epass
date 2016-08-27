<?php

function postHash($words) {
  global $INPUT_user,$INPUT_url,$INPUT_password,$SQL_upper,$SQL_lower,$SQL_number,$SQL_special,$SQL_size,$SQL_version;
  $str = $INPUT_user . $INPUT_url . $INPUT_password. $SQL_version;
  $val = doHash($SQL_upper,$SQL_lower,$SQL_number,$SQL_special,$SQL_size,$str);
  return("<center>
            <pre>$words <input $style type='text' readonly='readonly' value='$val'></pre>
          </center>");
}

function openHTML() {
  $res = '<html><head><title>epass</title><style type="text/css"></style></head><body text="#000000" bgcolor="#ffdead" link="#0000ee" vlink="#551a8b" alink="#ff0000"><form name="splash" method="post" action="epass.php">';
  return($res);
  }
  
function closeHTML() {
  $res = '</html></body></form>';
  return($res);
}

function requestHash() {
  return('
<td align="center" colspan="3">
  <input name="restoreButton" 
         title="Restores the default settings for the website and generates the new password" 
         type="submit"   
         value="Restore Defaults"/>
</td>
<td align="center" colspan="3">
  <input name="defaultButton" 
         title="Changes the default settings for the website and generates the new password and old password" 
         type="submit"   
         value="Change Password"/>
</td>');                                                   
}

function passInfo() {
  global $INPUT_url, $INPUT_user, $INPUT_password;
  return("<center>   
    <pre>         Website: <input $style type='text'     readonly='readonly' name = url      value='$INPUT_url'></pre>
    <pre>       User Name: <input $style type='text'     readonly='readonly' name = user     value='$INPUT_user'></pre>
    <pre> Master Password: <input $style type='password' readonly='readonly' name = password value='$INPUT_password'></pre>
    </center>");
} 

function hR() {
  return('<br><hr width="100%">');
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

function characterSelection() {
  global $SQL_upper, $SQL_lower, $SQL_number, $SQL_special, $SQL_size;
  $upperC   = optionSelect("upper",0,2,$SQL_upper);
  $lowerC   = optionSelect("lower",0,2,$SQL_lower);
  $numberC  = optionSelect("number",0,2,$SQL_number); 
  $specialC = optionSelect("special",0,2,$SQL_special);
  $sizeSelect = optionSelect("size",8,16,$SQL_size);
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
?>
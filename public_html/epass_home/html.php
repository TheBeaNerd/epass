<?php

function mainTable($rows) {
  $res = "<!doctype html>
<html style='height: 100%;'>
  <head>
    <meta http-equiv='content-type' content='text/html; charset=windows-1252'/>
    <title>epass</title>
    <style type='text/css'>
option {font-family: courier; font-size: 3vh;}
select {font-family: courier; font-size: 3vh;}
td {font-family: courier; font-size: 3vh;}
input {font-family: courier; font-size: 3vh;}
    </style>
  </head>
  <body alink='#ff0000' bgcolor='#ffdead' link='#0000ee' style='height: 100%;' text='#000000' vlink='#551a8b'>
    <form name='splash' method='post' action='epass.php'>
      <table align='center'>
        <tbody>\n";
  $res .= $rows;
  $res .= "</tbody>
      </table>
    </form>
  </body>
</html>\n";
  return($res);
}

function tableRow($entry) {
    $res  = "          <tr>
             <td>
               <center>\n";
    $res .= $entry;
    $res .= "               </center>
             </td>
           </tr>\n";
    return($res);
}

function postHash($patch,$words) {
    global $INPUT_user,$INPUT_url,$INPUT_password,$SQL_upper,$SQL_lower,$SQL_number,$SQL_special,$SQL_size,$SQL_version,$SQL_versionInc;
    $version = $SQL_version + $SQL_versionInc;
    if ($version < 1) {
        $version = 1;
    }
    $str = $INPUT_user . $INPUT_url . $INPUT_password. $version;
    $val = doHash($patch,$SQL_upper,$SQL_lower,$SQL_number,$SQL_special,$SQL_size,$str);
    $res  = "      <center>\n";
    $res .= "        <font>" . $words . "&nbsp;</font><input $style type='text' readonly='readonly' value='$val'>\n";
    $res .= "      </center>\n";
    return($res);
}

function passInfo($padding) {
  global $INPUT_url, $INPUT_user, $INPUT_password;
  $ENC_url = htmlentities($INPUT_url,ENT_QUOTES);
  $ENC_user = htmlentities($INPUT_user,ENT_QUOTES);
  $ENC_password = htmlentities($INPUT_password,ENT_QUOTES);
  return("
      <center>
        <font>" . $padding . "Website:&nbsp;</font><input $style type='text' readonly='readonly' name='url' value='$ENC_url'>
                       <input $style type='text' hidden='hidden' readonly='readonly' name='user'     value='$ENC_user'>
                       <input $style type='text' hidden='hidden' readonly='readonly' name='password' value='$ENC_password'>
      </center>\n");
} 

function settingInfo($padding) {
  global $INPUT_user,$INPUT_url,$INPUT_password,$SQL_upper,$SQL_lower,$SQL_number,$SQL_special,$SQL_size,$SQL_versionInc;
  $ENC_url = htmlentities($INPUT_url,ENT_QUOTES);
  $ENC_user = htmlentities($INPUT_user,ENT_QUOTES);
  $ENC_password = htmlentities($INPUT_password,ENT_QUOTES);
  return("
      <center>
        <font>" . $padding . "Website: </font><input $style type='text' readonly='readonly' name='url' value='$ENC_url'>
                       <input $style type='text' hidden='hidden' readonly='readonly' name='user'     value='$ENC_user'>
                       <input $style type='text' hidden='hidden' readonly='readonly' name='password' value='$ENC_password'>
                       <input $style type='text' hidden='hidden' readonly='readonly' name='upper'    value='$SQL_upper'>
                       <input $style type='text' hidden='hidden' readonly='readonly' name='lower'    value='$SQL_lower'>
                       <input $style type='text' hidden='hidden' readonly='readonly' name='number'   value='$SQL_number'>
                       <input $style type='text' hidden='hidden' readonly='readonly' name='special'  value='$SQL_special'>
                       <input $style type='text' hidden='hidden' readonly='readonly' name='size'     value='$SQL_size'>
                       <input $style type='text' hidden='hidden' readonly='readonly' name='versionInc'  value='$SQL_versionInc'>
      </center>\n");
} 

function hR() {
  return('      <br><hr width="100%">' . "\n");
}

function atleastSelect($name,$max,$value) {
  $opt=array_fill(0,$max,"");
  $opt[$value]=' selected="selected" ';
  $res="<select name='$name'>";
  $res .= "<option value=0  ".$opt[0].">0</option>";
  for ($i=1;$i<=$max;$i++) {
    $res .= "<option value='$i'  ".$opt[$i].">+ $i</option>";
  }
  $res .= "</select>";
  return($res);
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
  $sizeSelect = optionSelect("size",8,24,$SQL_size);
  $upperC   = atleastSelect("upper",2,$SQL_upper);
  $lowerC   = atleastSelect("lower",2,$SQL_lower);
  $numberC  = atleastSelect("number",2,$SQL_number); 
  $specialC = atleastSelect("special",2,$SQL_special);
  $res = 
"          <tr>
            <td>
              <br/>
              <center>
                <table cellspacing='1' cellpadding='5' border='3'>
                  <tbody>
                    <tr>
                      <th align='center' valign='middle'>Setting</th>
                      <th align='center' valign='middle'>&nbsp;Value&nbsp;</th>
                      <th align='center' valign='middle'>Range</th>
                    </tr>
                    <tr>
                      <td align='center' valign='middle'>Size</td>
                      <td align='center' valign='middle'>
                        $sizeSelect
                      </td>
                      <td align='center' valign='middle'>8 .. 24</td>
                    </tr>
                    <tr>
                      <td align='center' valign='middle'>&nbsp;Uppercase&nbsp;</td>
                      <td align='center' valign='middle'>
                        $upperC
                      </td>
                      <td align='center' valign='middle'>A .. Z</td>
                    </tr>
                    <tr>
                      <td align='center' valign='middle'>Lowercase</td>
                      <td align='center' valign='middle'>
                        $lowerC
                      </td>
                      <td align='center' valign='middle'>a .. z</td>
                    </tr>
                    <tr>
                      <td align='center' valign='middle'>Digits</td>
                      <td align='center' valign='middle'>
                        $numberC
                      </td>
                      <td align='center' valign='middle'>0 .. 9</td>
                    </tr>
                    <tr>
                      <td align='center' valign='middle'>Special</td>
                      <td align='center' valign='middle'>
                        $specialC
                      </td>
                      <td align='center' valign='middle'>&nbsp;!@#$%^&amp;*&nbsp;</td>
                    </tr>
                  </tbody>
                </table>
              </center>
          </tr>\n";
  return($res);
}

function versionSelection() {
    global $SQL_version;
    $res =
"          <tr>
            <td>
              <br/>
              <center>
                <table cellspacing='1' cellpadding='5' border='3'>
                  <tbody>
                    <tr>
                      <td align='center' valign='middle'><b>&nbsp;Version&nbsp;</b></td>
                      <td align='center' valign='middle'><font>&nbsp;".$SQL_version."&nbsp;</font></td>
                      <td>
                        <select name='versionInc' title='Update Password Version' >
                          <option value='0' selected='selected'>Unchanged</option>
                          <option value='1'>Update +1</option>
                          <option value='-1'>Rollback &minus;1</option>
                        </select>  
                      </td>
                    </tr>
                  </tbody>
                </table>
              </center>
            </td>
          </tr>\n";
  return($res);
}

function returnButton() {
    $res = 
'          <tr>
            <td>
              <br/>
              <center>
                <input name="returnButton"
                       value="&nbsp;Return&nbsp;"
                       title="Return to the ePass Home Page" 
                       type="submit"
                       />
              </center>
            </td>
          </tr>
';
    return($res);
}

function configurationButtons() {
  return('
          <tr>
            <td>
              <br/>
              <center>
                <input name="previewButton" 
                       value="&nbsp; Preview&nbsp; "
                       type="submit"   
                       title="Preview Configuration Results"
                       />
              </center>
            </td>
          </tr>
          <tr>
            <td>
              <center>
                <input name="returnButton" 
                       value="&nbsp;  Abort  &nbsp;"
                       title="Return to the ePass Home Page" 
                       type="submit"   
                       />
              </center>
            </td>
          </tr>
          <tr>
            <td>
              <center>
                <input name="patchButton" 
                       value="&nbsp;  Patch  &nbsp;"
                       title="Generates a Patched Password" 
                       type="submit"   
                        />
              </center>
            </td>
          </tr>
');                                                   
}

function previewButtons() {
    $res = 
"          <tr>
            <td>
              <br/>
              <center>
                <input name='applyButton' 
                       value='&nbsp;Save Updates&nbsp;&nbsp;'
                       title='Save the new settings for this URL' 
                       type='submit'
                       />
              </center>
            </td>
          </tr>
";
    $res .=
'          <tr>
            <td>
              <center>
                <input name="returnButton" 
                       value="Discard Updates"
                       title="Return to the ePass Home Page" 
                       type="submit"   
                       />
              </center>
            </td>
          </tr>
';
    return($res);
}

?>
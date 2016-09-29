<?php

//  $DBASE_name="sql_dbase_name";
//  $DBASE_user="sql_dbase_username";
//  $DBASE_pass="sql_dbase_password";
//  $HASH_salt='random_salt_valu';

$INPUT_url       = sanitizeURL($_POST['url']);
$INPUT_user      = sanitizeUser($_POST['user']);
$INPUT_password  = $_POST['password'];

$manualButton    = $_POST['manualButton'];
$passwordButton  = $_POST['passwordButton'];
$settingsButton  = $_POST['settingsButton'];
$restoreButton   = $_POST['restoreButton'];
$changeButton    = $_POST['changeButton'];
$patchButton     = $_POST['patchButton'];
$defaultButton   = $_POST['defaultButton'];

$POST_Upper       = $_POST['upper'];
$POST_Lower       = $_POST['lower'];
$POST_Number      = $_POST['number'];
$POST_Special     = $_POST['special'];
$POST_Size        = $_POST['size'];

$SQL_upper       = 1;
$SQL_lower       = 1;
$SQL_number      = 1;
$SQL_special     = 0;
$SQL_size        = 10;
$SQL_version     = 1;

function getPostData() {
    global $SQL_upper, $SQL_lower, $SQL_number, $SQL_special, $SQL_size, $POST_Upper, $POST_Lower, $POST_Number, $POST_Special, $POST_Size;
    $SQL_upper   = $POST_Upper;
    $SQL_lower   = $POST_Lower;
    $SQL_number  = $POST_Number;
    $SQL_special = $POST_Special;
    $SQL_size    = $POST_Size;
}

?>

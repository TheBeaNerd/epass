<?php

//  $DBASE_name="sql_dbase_name";
//  $DBASE_user="sql_dbase_username";
//  $DBASE_pass="sql_dbase_password";
//  $HASH_salt='random_salt_valu';

$INPUT_url       = sanitizeURL($_POST['url']);
$INPUT_user      = sanitizeUser($_POST['user']);
$INPUT_password  = $_POST['password'];

$passwordButton  = $_POST['passwordButton'];
$settingsButton  = $_POST['settingsButton'];
$manualButton    = $_POST['manualButton'];
$previewButton   = $_POST['previewButton'];
$returnButton    = $_POST['returnButton'];
$patchButton     = $_POST['patchButton'];
$applyButton     = $_POST['applyButton'];

$POST_Size       = $_POST['size'];
$POST_Upper      = $_POST['upper'];
$POST_Lower      = $_POST['lower'];
$POST_Number     = $_POST['number'];
$POST_Special    = $_POST['special'];
$POST_VersionInc = $_POST['versionInc'];

$SQL_upper       = 1;
$SQL_lower       = 1;
$SQL_number      = 1;
$SQL_special     = 0;
$SQL_size        = 10;
$SQL_versionInc  = 0;
$SQL_version     = 1;

function getPostData() {
    global $SQL_upper, $SQL_lower, $SQL_number, $SQL_special, $SQL_size, $SQL_versionInc, $POST_Upper, $POST_Lower, $POST_Number, $POST_Special, $POST_Size, $POST_VersionInc;
    getSavedSettings();
    $SQL_size       = $POST_Size;
    $SQL_upper      = $POST_Upper;
    $SQL_lower      = $POST_Lower;
    $SQL_number     = $POST_Number;
    $SQL_special    = $POST_Special;
    $SQL_versionInc = $POST_VersionInc;
}

?>

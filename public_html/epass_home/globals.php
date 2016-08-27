<?php
$generateButton  = $_POST['generateButton'];
$manualButton    = $_POST['manualButton'];
$passwordButton  = $_POST['passwordButton'];
$settingsButton  = $_POST['settingsButton'];
$restoreButton   = $_POST['restoreButton'];
$changeButton    = $_POST['changeButton'];
$defaultButton   = $_POST['defaultButton'];
$postUpper       = $_POST['upper'];
$postLower       = $_POST['lower'];
$postNumber      = $_POST['number'];
$postSpecial     = $_POST['special'];
$postSize        = $_POST['size'];
$url             = sanitizeURL($_POST['url']);
$user            = sanitizeUser($_POST['user']);
$password        = $_POST['password'];

function getPostData() {
    global $upper, $lower, $number, $special, $size, $postUpper, $postLower, $postNumber, $postSpecial, $postSize;
    $upper = $postUpper;
    $lower = $postLower;
    $number = $postNumber;
    $special = $postSpecial;
    $size = $postSize;
}

?>

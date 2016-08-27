<?php
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off") {
    header('Location: https://ns162.websitewelcome.com/~dagreve/epass.php',true,302);
    exit();
}
if (FALSE == @include __DIR__ . '/../sql.php') {
  exit();
}
$epass_home = __DIR__ . '/epass_home/';
include $epass_home . 'util.php';
include $epass_home . 'globals.php';
include $epass_home . 'sql.php';
include $epass_home . 'hash.php';
include $epass_home . 'html.php';
include $epass_home . 'epass.php';
epass();
?>
<?php
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off") {
    header('Location: https://ns162.websitewelcome.com/~dagreve/epass.php',true,302);
    exit();
}
$epass_home = __DIR__ . '/epass_home/';
include $epass_home . 'epass.php';
epass();
?>
<?php
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
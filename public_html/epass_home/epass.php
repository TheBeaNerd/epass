<?php

function epass() {
  // This is the top level epass() function.
  global $manualButton, $passwordButton, $settingsButton, $restoreButton, $patchButton, $changeButton, $defaultButton;
  if (isset($manualButton)) {
    generateManualPage();
  } elseif (nullInput()) {
    generateSplashPage();
  } elseif (isset($settingsButton)) {
    generateSettings();
  } elseif (isset($restoreButton)) {
    restoreDefaults();
  } elseif (isset($changeButton)) {
    changePassword(); 
  } elseif (isset($patchButton)) {
    patchPassword(); 
  } elseif (isset($passwordButton)) {
    generatePassword();
  } elseif (isset($defaultButton)) {
    changeDefault();
  } else {
    // Default Behavior
    generateSplashPage();
  }
}

function generateManualPage() {
  // Here is an example of loading a raw HTML file directly ..
  global $epass_home;
  include $epass_home . "manual.html";
}

function generateSplashPage() {
  global $epass_home;
  include $epass_home . "index.html";
}

function generatePassword() {
    global $SQL_upper, $SQL_lower, $SQL_number, $SQL_special, $SQL_size, $INPUT_url, $INPUT_password, $INPUT_user;
    $res = openHTML();
    $res .= passInfo();
    getDefaultSettings();
    $res .= hR(); 
    $res .= postHash(False,'Copy and Paste');
    $res .= hR();
    $res .= closeHTML();
    echo($res);
  }
  
function generateSettings() {
    global $SQL_upper, $SQL_lower, $SQL_number, $SQL_special, $SQL_size, $INPUT_url, $INPUT_password, $INPUT_user;
    $res = openHTML();
    $res .= passInfo();
    getDefaultSettings();
    $res .= characterSelection();
    $res .= requestHash(); 
    $res .= hR();
    $res .= closeHTML();
    echo($res);
}
      
function restoreDefaults() {
  global $SQL_upper, $SQL_lower, $SQL_number, $SQL_special, $SQL_size, $INPUT_url, $INPUT_password, $INPUT_user;
  $res = openHTML();
  $res .= passInfo();
  getSavedSettings();
  getPostData();
  saveSettings(0);
  $res .= hR();    
  $res .= postHash(False,'Copy and Paste');
  $res .= hR();
  $res .= closeHTML();
  echo($res);
  }
  
function changePassword() {
  global $SQL_upper, $SQL_lower, $SQL_number, $SQL_special, $SQL_size, $INPUT_url, $INPUT_password, $INPUT_user;
  $res = openHTML();
  $res .= passInfo();
  getDefaultSettings();
  $res .= hR();    
  $res .= postHash(False,'Old Password');
  $res .= hR();
  getDefaultSettings();
  saveSettings(1);  
  $res .= postHash(False,'New Password');
  $res .= hR();
  $res .= closeHTML();
  echo($res);
  }

function patchPassword() {
  global $SQL_upper, $SQL_lower, $SQL_number, $SQL_special, $SQL_size, $INPUT_url, $INPUT_password, $INPUT_user;
  $res = openHTML();
  $res .= passInfo();
  getDefaultSettings();
  $res .= hR();    
  $res .= postHash(False,'Old Password');
  $res .= hR();
  getPostData();
  $res .= postHash(True,'New Password');
  $res .= hR();
  $res .= closeHTML();
  echo($res);
  }

function changeDefault() {
  global $SQL_upper, $SQL_lower, $SQL_number, $SQL_special, $SQL_size, $INPUT_url, $INPUT_password, $INPUT_user;
  $res = openHTML();
  $res .= passInfo();
  getDefaultSettings();
  $res .= hR();    
  $res .= postHash(False,'Old Password');
  $res .= hR();
  getPostData();
  saveSettings(0);
  $res .= postHash(False,'New Password');
  $res .= hR();
  $res .= closeHTML();
  echo($res);
}

?>

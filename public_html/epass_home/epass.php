<?php

function epass() {
  // This is the top level epass() function.
  global $generateButton, $manualButton, $passwordButton, $settingsButton, $restoreButton, $changeButton, $defaultButton;
  if (isset($manualButton)) {
    generateManualPage();
  } elseif ((isset($settingsButton))&&(notNull())) {
    generateSettings();
  } elseif (isset($restoreButton)) {
    restoreDefaults();
  } elseif ((isset($changeButton))&&(notNull())) {
    changePassword(); 
  } elseif ((isset($passwordButton))&&(notNull())) {
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
    setDefaultSettings();
    $res .= hR(); 
    $res .= postHash('Copy and Paste');
    $res .= hR();
    $res .= closeHTML();
    echo($res);
  }
  
function generateSettings() {
    global $SQL_upper, $SQL_lower, $SQL_number, $SQL_special, $SQL_size, $INPUT_url, $INPUT_password, $INPUT_user;
    $res = openHTML();
    $res .= passInfo();
    setDefaultSettings();
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
  getChangedSettings();
  getPostData();
  setChanges(0);
  $res .= hR();    
  $res .= postHash('Copy and Paste');
  $res .= hR();
  $res .= closeHTML();
  echo($res);
  }
  
function changePassword() {
  global $SQL_upper, $SQL_lower, $SQL_number, $SQL_special, $SQL_size, $INPUT_url, $INPUT_password, $INPUT_user;
  $res = openHTML();
  $res .= passInfo();
  setDefaultSettings();
  $res .= hR();    
  $res .= postHash('Old Password');
  $res .= hR();
  setDefaultSettings();
  setChanges(1);  
  $res .= postHash('New Password');
  $res .= hR();
  $res .= closeHTML();
  echo($res);
  }

function changeDefault() {
  global $SQL_upper, $SQL_lower, $SQL_number, $SQL_special, $SQL_size, $INPUT_url, $INPUT_password, $INPUT_user;
  $res = openHTML();
  $res .= passInfo();
  setDefaultSettings();
  $res .= hR();    
  $res .= postHash('Old Password');
  $res .= hR();
  getPostData();
  setChanges(0);
  $res .= postHash('New Password');
  $res .= hR();
  $res .= closeHTML();
  echo($res);
}

?>

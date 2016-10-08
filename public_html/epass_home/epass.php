<?php

// Proposed:
//
// Main page:
// - passwordButton
// - settingsButton
// - manualButton
// Settings page:
// - previewButton
// - returnButton
// - patchButton
// Preview page:
// - applyButton
// - returnButton

function epass() {
    // This is the top level epass() function.
    global $manualButton, $passwordButton, $settingsButton, $previewButton, $patchButton, $returnButton, $applyButton;
    //
    // Main Page
    if (isset($manualButton)) {
        generateManualPage();
    } elseif (nullInput()) {
        generateSplashPage();
    } elseif (isset($passwordButton)) {
        generatePasswordPage();
    } elseif (isset($settingsButton)) {
        generateSettingsPage();
    // Settings Page
    } elseif (isset($previewButton)) {
        generatePreviewPage(True);
    } elseif (isset($patchButton)) {
        generatePreviewPage(False);
    // Preview Page
    } elseif (isset($applyButton)) {
        applySettings();
    } else {
        // returnButton
        // Default Behavior
        generateSplashPage();
    }
}

function generateManualPage() {
  global $epass_home;
  include $epass_home . "manual.html";
}

function generateSplashPage() {
  global $epass_home;
  include $epass_home . "index.html";
}

function applySettings() {
    getPostData();
    saveSettings();
    generateSplashPage();
}

function generatePasswordPage() {
    $res  = tableRow("<br/>\n" . passInfo("&nbsp;&nbsp;&nbsp;"));
    getSavedSettings();
    $res .= tableRow("<br/>\n" . postHash(True,'Copy/Paste:&nbsp;'));
    $res .= returnButton();
    $res = mainTable($res);
    echo($res);
  }
  
function generateSettingsPage() {
    $res = tableRow("<br/>\n" . passInfo(""));
    getSavedSettings();
    $res .= characterSelection();
    $res .= versionSelection();
    $res .= configurationButtons();
    $res = mainTable($res);
    echo($res);
}
      
function generatePreviewPage($patch) {
    getSavedSettings();
    $res0  = tableRow(postHash($patch,'Old Password:'));
    getPostData();
    $res1  = tableRow("<br/>\n" . settingInfo("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"));
    $res1 .= $res0;
    $res2  = tableRow(postHash(True,'New Password:'));
    $res2 .= previewButtons();
    $res = mainTable($res1 . $res2);
    echo($res);
}

?>

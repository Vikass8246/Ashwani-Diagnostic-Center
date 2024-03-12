<?php
require 'connection.php';
require 'vendor/autoload.php'; // Include Facebook SDK

// Set your Facebook API credentials
$fb = new Facebook\Facebook([
  'app_id' => 'YOUR_FACEBOOK_APP_ID',
  'app_secret' => 'YOUR_FACEBOOK_APP_SECRET',
  'default_graph_version' => 'v12.0',
]);

$helper = $fb->getRedirectLoginHelper();
$permissions = ['email']; // Add additional permissions if needed

$loginUrl = $helper->getLoginUrl('YOUR_REDIRECT_URI', $permissions);

header("Location: $loginUrl");
?>
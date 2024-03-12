<?php
require 'connection.php';
// require 'vendor/autoload.php'; // Include LinkedIn SDK

// Set your LinkedIn API credentials
$clientId = 'YOUR_LINKEDIN_CLIENT_ID';
$clientSecret = 'YOUR_LINKEDIN_CLIENT_SECRET';
$redirectUri = 'YOUR_REDIRECT_URI';

$linkedin = new Happyr\LinkedIn\LinkedIn($clientId, $clientSecret);
$url = $linkedin->getLoginUrl(['redirect_uri' => $redirectUri]);

header("Location: $url");
?>
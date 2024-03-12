<?php
require 'connection.php';
require 'vendor/autoload.php'; // Include Google API client library

// Set your Google API credentials
$client = new Google_Client();
$client->setClientId('537669524048-jk2h8e5t7acq49s99r9v1ij6ei03kdq3.apps.googleusercontent.com');
$client->setClientSecret('YOUR_GOOGLE_CLIENT_SECRET');
$client->setRedirectUri('YOUR_REDIRECT_URI');
$client->addScope('email');
$client->addScope('profile');

$authUrl = $client->createAuthUrl();
header("Location: $authUrl");

?>
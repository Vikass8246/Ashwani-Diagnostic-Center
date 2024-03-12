<?php
require 'connection.php';
require 'vendor/autoload.php'; // Include Twitter SDK

// Set your Twitter API credentials
$apiKey = 'YOUR_TWITTER_API_KEY';
$apiSecretKey = 'YOUR_TWITTER_API_SECRET_KEY';
$redirectUri = 'YOUR_REDIRECT_URI';

$twitter = new Abraham\TwitterOAuth\TwitterOAuth($apiKey, $apiSecretKey);
$request_token = $twitter->oauth('oauth/request_token', ['oauth_callback' => $redirectUri]);
$url = $twitter->url('oauth/authenticate', ['oauth_token' => $request_token['oauth_token']]);

header("Location: $url");
?>
<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

session_start();

$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URI);
$client->addScope('email');
$client->addScope('profile');

if (!isset($_GET['code'])) {
    header('Location: login.php');
    exit;
}

$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
$client->setAccessToken($token);

$oauth2 = new Google_Service_Oauth2($client);
$userInfo = $oauth2->userinfo->get();

// Example output of user information
header('Content-Type: application/json');
echo json_encode($userInfo);

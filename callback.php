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

// Convert Google user info to a simple array
$currentUser = [
    'id' => $userInfo->id,
    'name' => $userInfo->name,
    'email' => $userInfo->email,
];

// Path to text file where users are stored
$usersFile = __DIR__ . '/users.txt';
$existing = [];

if (file_exists($usersFile)) {
    $lines = file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $decoded = json_decode($line, true);
        if ($decoded) {
            $existing[] = $decoded;
        }
    }
}

$foundUser = null;
foreach ($existing as $user) {
    if ($user['email'] === $currentUser['email']) {
        $foundUser = $user;
        break;
    }
}

// Append user to file on first login (signup)
if ($foundUser === null) {
    file_put_contents($usersFile, json_encode($currentUser) . PHP_EOL, FILE_APPEND);
    $foundUser = $currentUser;
}

$_SESSION['user'] = $foundUser;

header('Content-Type: application/json');
echo json_encode($foundUser);

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// save_chat.php

// Ensure that this script only responds to POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method Not Allowed');
}

// Get the data from the POST request
$sessionId = $_POST['sessionId'] ?? '';
$sender = $_POST['sender'] ?? '';
$message = $_POST['message'] ?? '';

var_dump($_POST);

// Validate the data
if (empty($sessionId) || empty($sender) || empty($message)) {
    http_response_code(400);
    die('Bad Request: Missing required fields');
}

// Sanitize the data
$sessionId = htmlspecialchars($sessionId);
$sender = htmlspecialchars($sender);
$message = htmlspecialchars($message);

// Create a timestamp
$timestamp = date('Y-m-d H:i:s');

// Prepare the log entry
$logEntry = "$timestamp | Session: $sessionId | $sender: $message\n";

// Define the log file path
$logFile =  __DIR__ . '/chat_logs.txt';

// Test file writing
$testWrite = @file_put_contents($logFile, "Test write\n", FILE_APPEND | LOCK_EX);
if ($testWrite === false) {
    $error = error_get_last();
    die('Error: Unable to write to log file.' . $error['message']);
}

// Append the log entry to the file
if (file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX) === false) {
    http_response_code(500);
    die('Error: Unable to save the chat message');
}

// Send a success response
echo 'Message saved successfully';
?>
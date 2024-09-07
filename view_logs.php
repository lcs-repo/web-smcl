<?php

// Read the log file
$logFile = 'chat_logs.txt';
// Stores the result of file() function 
$logs = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$sessions = [];
foreach ($logs as $log) {
    if (strpos($log, 'Session:') !== false) {
        preg_match('/Session: (session_[^\s|]+)/', $log, $matches);
        $sessionId = $matches[1];
        $timestamp = substr($log, 0, 19);
        $message = substr($log, strpos($log, '|', 20) + 2);

        if (!isset($sessions[$sessionId])) {
            $sessions[$sessionId] = [];
        }
        $sessions[$sessionId][] = ['timestamp' => $timestamp, 'message' => $message];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Logs Viewer</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; }
        h1 { color: #333; }
        .session { margin-bottom: 20px; border: 1px solid #ddd; padding: 10px; }
        .session-header { background-color: #f0f0f0; padding: 5px; margin-bottom: 10px; }
        .message { margin-bottom: 5px; }
        .timestamp { color: #555; font-size: 0.9em; }
        #search { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Chat Logs Viewer</h1>

    <div id="search">
        <input type="text" id="searchInput" placeholder="Search logs...">
        <button onClick="searchLogs()">Search</button>
    </div>

    <?php foreach ($sessions as $sessionId => $messages): ?>
        <div class="session">
            <div class="session-header">Session ID: <?php echo htmlspecialchars($sessionId); ?></div>
            <?php foreach ($messages as $message): ?>
                <div class="message">
                    <span class="timestamp"><?php echo htmlspecialchars($message['timestamp']); ?></span>
                    <?php echo htmlspecialchars($message['message']); ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <script>
        function searchLogs() {
            var input, filter, sessions, i, txtValue;
            input = document.getElementById('searchInput');
            filter = input.value.toUpperCase();
            sessions = document.getElementsByClassName('session');

            for (i = 0; i < sessions.length; i++) {
                txtValue = sessions[i].textContent || sessions[i].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    sessions[i].style.display = "";
                } else {
                    sessions[i].style.display = "none";
                }
            }
        }
    </script>
</body>
</html>


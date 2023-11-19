<?php
$hummusToken = '';

$host = '';
$dbname = '';
$username = '';
$password = '';

$channelId = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$apiUrl = "https://hummus.sys42.net/api/v6/channels/$channelId/messages";
$headers = [
    'Authorization: ' . $hummusToken,
];

$curl = curl_init($apiUrl);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($curl);
curl_close($curl);

if (!$response) {
    die("Error fetching messages from Hummus API.");
}

$messages = json_decode($response, true);

foreach ($messages as $message) {
    $messageId = $message['id'];
    $content = $message['content'];
    $authorId = $message['author']['id'];
    $timestamp = date('Y-m-d H:i:s', strtotime($message['timestamp']));

    try {
        $stmt = $pdo->prepare("SELECT * FROM hummus_messages WHERE message_id = ?");
        $stmt->execute([$messageId]);
        $existingMessage = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$existingMessage) {
            $stmt = $pdo->prepare("INSERT INTO hummus_messages (channel_id, message_id, content, author_id, timestamp) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$channelId, $messageId, $content, $authorId, $timestamp]);
            echo "<text style=color:green>Message added to the database: $content\n</text><br>";
        } else {
            echo "<text style=color:red>Message already exists in the database: $content\n</text><br>";
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}

?>

<?php
require_once('Settings.php');
$conn = new mysqli($db_server, $db_user, $db_passwd, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "--- All Directory Settings ---\n";
$result = $conn->query("SELECT `key`, `value` FROM settings");
while($row = $result->fetch_assoc()) {
    echo "{$row['key']}: {$row['value']}\n";
}

$conn->close();
?>

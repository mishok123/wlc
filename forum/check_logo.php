<?php
require_once('Settings.php');
$conn = new mysqli($db_server, $db_user, $db_passwd, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "--- SMF Settings ---\n";
$result = $conn->query("SELECT variable, value FROM {$db_prefix}settings WHERE variable IN ('header_logo_url', 'forum_name')");
while($row = $result->fetch_assoc()) {
    echo "{$row['variable']}: {$row['value']}\n";
}

echo "\n--- SMF Themes ---\n";
$result = $conn->query("SELECT id_theme, variable, value FROM {$db_prefix}themes WHERE variable IN ('header_logo_url', 'logo_url')");
while($row = $result->fetch_assoc()) {
    echo "Theme {$row['id_theme']} - {$row['variable']}: {$row['value']}\n";
}

$conn->close();
?>

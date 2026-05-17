<?php
require_once('Settings.php');
$conn = new mysqli($db_server, $db_user, $db_passwd, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "--- SMF Settings (Like %logo%) ---\n";
$result = $conn->query("SELECT variable, value FROM {$db_prefix}settings WHERE variable LIKE '%logo%'");
while($row = $result->fetch_assoc()) {
    echo "{$row['variable']}: {$row['value']}\n";
}

echo "\n--- SMF Themes (Like %logo%) ---\n";
$result = $conn->query("SELECT id_theme, variable, value FROM {$db_prefix}themes WHERE variable LIKE '%logo%'");
while($row = $result->fetch_assoc()) {
    echo "Theme {$row['id_theme']} - {$row['variable']}: {$row['value']}\n";
}

// Also check forum name
echo "\n--- Forum Name ---\n";
$result = $conn->query("SELECT value FROM {$db_prefix}settings WHERE variable = 'forum_name'");
if($row = $result->fetch_assoc()) {
    echo "forum_name: {$row['value']}\n";
}

$conn->close();
?>

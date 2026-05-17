<?php
require_once('Settings.php');
$conn = new mysqli($db_server, $db_user, $db_passwd, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$res = $conn->query("SHOW TABLES");
echo "--- Tables ---\n";
while($row = $res->fetch_array()) {
    echo $row[0] . "\n";
}

$conn->close();
?>

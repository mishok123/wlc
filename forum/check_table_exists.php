<?php
require_once('Settings.php');
$conn = new mysqli($db_server, $db_user, $db_passwd, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$res = $conn->query("SHOW TABLES LIKE 'settings'");
if($res->num_rows > 0) {
    echo "Table 'settings' exists.\n";
} else {
    echo "Table 'settings' does NOT exist.\n";
    $res = $conn->query("SHOW TABLES");
    echo "Current tables:\n";
    while($row = $res->fetch_array()) {
        echo $row[0] . "\n";
    }
}

$conn->close();
?>

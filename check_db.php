<?php
// check_db.php
$mysqli = new mysqli("localhost", "root", "", "monika");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$result = $mysqli->query("DESCRIBE users");
echo "Table 'users' columns:\n";
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}

echo "\nTable 'pml_activities' exists? ";
$result = $mysqli->query("SHOW TABLES LIKE 'pml_activities'");
echo ($result->num_rows > 0 ? "YES" : "NO") . "\n";

echo "\nTable 'audit_logs' exists? ";
$result = $mysqli->query("SHOW TABLES LIKE 'audit_logs'");
echo ($result->num_rows > 0 ? "YES" : "NO") . "\n";

$mysqli->close();

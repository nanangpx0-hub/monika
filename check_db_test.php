<?php
// check_db_test.php
$mysqli = new mysqli("localhost", "root", "", "monika_test");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$result = $mysqli->query("SHOW TABLES");
echo "Tables in monika_test:\n";
while ($row = $result->fetch_row()) {
    echo $row[0] . "\n";
}

$result = $mysqli->query("DESCRIBE users");
if ($result) {
    echo "\nTable 'users' columns:\n";
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . "\n";
    }
}

$mysqli->close();

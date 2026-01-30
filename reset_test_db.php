<?php
// reset_test_db.php
$mysqli = new mysqli("localhost", "root", "", "monika_test");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Disable foreign key checks
$mysqli->query("SET FOREIGN_KEY_CHECKS = 0");

// Get all tables
$result = $mysqli->query("SHOW TABLES");
while ($row = $result->fetch_row()) {
    $table = $row[0];
    echo "Dropping table $table... ";
    if ($mysqli->query("DROP TABLE $table")) {
        echo "OK\n";
    } else {
        echo "Error: " . $mysqli->error . "\n";
    }
}

// Enable foreign key checks
$mysqli->query("SET FOREIGN_KEY_CHECKS = 1");

echo "All tables dropped.\n";

// Verify tables are gone
$result = $mysqli->query("SHOW TABLES");
if ($result->num_rows > 0) {
    echo "Warning: Some tables still exist:\n";
    while ($row = $result->fetch_row()) {
        echo "- " . $row[0] . "\n";
    }
} else {
    echo "Verification: Database is empty.\n";
}

$mysqli->close();

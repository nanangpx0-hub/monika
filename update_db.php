<?php
$host = 'localhost';
$db   = 'monika';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // 1. Expand ENUM to include 'Setor'
    echo "Expanding ENUM...\n";
    $pdo->exec("ALTER TABLE dokumen_survei MODIFY COLUMN status ENUM('Setor', 'Uploaded', 'Sudah Entry', 'Error', 'Valid') DEFAULT 'Setor'");
    
    // 2. Update data
    echo "Updating data...\n";
    $stmt = $pdo->prepare("UPDATE dokumen_survei SET status = 'Setor' WHERE status = 'Uploaded'");
    $stmt->execute();
    echo "Updated " . $stmt->rowCount() . " rows.\n";
    
    // 3. Shrink ENUM to remove 'Uploaded'
    echo "Finalizing ENUM...\n";
    $pdo->exec("ALTER TABLE dokumen_survei MODIFY COLUMN status ENUM('Setor', 'Sudah Entry', 'Error', 'Valid') DEFAULT 'Setor'");
    
    echo "Done.\n";
    
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
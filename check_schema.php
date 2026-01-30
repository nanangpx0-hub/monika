<?php
$db = new PDO('mysql:host=localhost;dbname=monika', 'root', '');
$stmt = $db->query('DESCRIBE users');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

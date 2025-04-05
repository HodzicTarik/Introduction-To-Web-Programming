<?php
// Konekcija sa bazom pomoću PDO
$host = 'localhost';
$port = '3307';
$dbname = 'findacar';
$username = 'root';
$password = 'novasifra';
$charset = 'utf8';

$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";

function getDatabaseConnection() {
    global $dsn, $username, $password;
    
    try {
        $db = new PDO($dsn, $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $db;
    } catch (PDOException $e) {
        die("❌ Database connection failed: " . $e->getMessage());
    }
}
?>

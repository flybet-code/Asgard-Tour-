<?php
// DB_Bifrost.php
$host = 'localhost'; // Database host
$dbname = 'AsgardTour'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password (empty for local, replace with your credentials)

try {
    // Create a new PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Return the PDO instance for use in other files
    return $pdo;
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit; // Exit if connection fails
}
?>

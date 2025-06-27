<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include the database connection
    $pdo = require 'DB_Bifrost.php';
    
    // Sanitize and retrieve form inputs
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Fetch user record
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password_hash'])) {
        // Set session variables for logged-in user
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];
        
        // Redirect based on role
        if ($user['role'] === 'Admin') {
            header("Location: adminpanel.php");
            exit;
        } else {
            header("Location: traveler.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "Invalid email or password!";
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>

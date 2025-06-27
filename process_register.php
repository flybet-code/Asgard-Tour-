<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include the database connection
    $pdo = require 'DB_Bifrost.php';
    
    // Sanitize and retrieve form inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Check if user already exists
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION['error'] = "User with this email already exists!";
        header("Location: index.php");
        exit;
    }
    
    // Hash the password before storing
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user (default role: Traveler)
    $stmt = $pdo->prepare("INSERT INTO Users (name, email, password_hash, role) VALUES (?, ?, ?, 'Traveler')");
    if ($stmt->execute([$name, $email, $password_hash])) {
        $_SESSION['success'] = "Registration successful. Please login.";
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['error'] = "Registration failed. Please try again.";
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>

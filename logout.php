<?php
// logout.php
session_start(); // Start the session

// Check if session variables are set
if (isset($_SESSION['user_id'])) {
    // Unset all of the session variables associated with the user
    unset($_SESSION['user_id']);
    unset($_SESSION['role']); // Also unset any other roles or user-related variables as needed
}

// Destroy the session
session_destroy(); // Destroy the session completely

// Optionally clear the session cookie if it exists
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect to login and register page
header("Location: loginandregister.php");
exit();
?>
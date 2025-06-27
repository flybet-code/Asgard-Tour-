<?php
// add_admin.php

// Include the DB connection file
$pdo = require 'DB_bifrost.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and trim whitespace
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    // Role is set to Admin by default. (The form dropdown is also preset to Admin.)
    $role     = 'Admin';
    
    // Basic server-side validation: ensure no field is empty
    if (empty($name) || empty($email) || empty($password)) {
        $message = "Please fill in all required fields.";
    } else {
        // Check if the email already exists in the Users table
        $stmt = $pdo->prepare("SELECT * FROM Users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $message = "The email address is already in use. Please use a different email.";
        } else {
            // Securely hash the password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert the new admin record
            $stmt = $pdo->prepare("INSERT INTO Users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$name, $email, $password_hash, $role])) {
                $message = "New admin added successfully.";
            } else {
                $message = "An error occurred while adding the admin.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Admin</title>
    <style>
        /* Overall page and container styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f8;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        /* Header styling */
        h1 {
            margin-bottom: 10px;
            font-size: 24px;
        }
        h2 {
            margin-top: 0;
            font-size: 16px;
            color: #666;
        }
        /* Form styling */
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 15px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s;
        }
        input:focus {
            border-color: #007BFF;
            outline: none;
        }
        /* Button styling */
        .button-group {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }
        button {
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: opacity 0.3s;
        }
        button.add {
            background-color: #28a745;
            color: white;
        }
        button.cancel {
            background-color: #6c757d;
            color: white;
            margin-left: 10px;
        }
        button:hover {
            opacity: 0.9;
        }
        /* Message styling */
        .error {
            color: red;
            margin-top: 10px;
        }
        .success {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add New Admin</h1>
        <h2>Please enter the administrator details</h2>
        
        <!-- Display feedback message -->
        <?php if ($message): ?>
            <p class="<?php echo (strpos($message, 'successfully') !== false) ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>
        
        <form action="" method="post">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" placeholder="Enter full name" required>
            
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="Enter email address" required>
            
            <label for="role">Role</label>
            <select id="role" name="role">
                <option value="Admin" selected>Admin</option>
                <!-- Future roles can be added here if needed -->
            </select>
            
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter password" required>
            
            <div class="button-group">
                <button type="submit" class="add">Add Admin</button>
                <button type="button" class="cancel" onclick="window.location.href='dashboard.php';">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>

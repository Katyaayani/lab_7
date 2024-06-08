<?php
session_start();
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $password = $_POST['password'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'Lab_7');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "SELECT * FROM users WHERE matric=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Fetch the user data
        $user = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['matric'] = $matric;
            header('Location: display.php');
            exit();
        } else {
            $error_message = 'Invalid username or password, <a href="login.php">Login</a> again.';
        }
    } else {
        $error_message = 'Invalid username or password, <a href="login.php">Login</a> again.';
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <?php
    if (!empty($error_message)) {
        echo '<p style="color:black;">' . $error_message . '</p>';
    }
    ?>
    <form method="POST" action="login.php">
        Matric: <input type="text" name="matric" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" value="Login">
    </form>
    <a href="register.php">Register</a> here if you have not.
</body>
</html>

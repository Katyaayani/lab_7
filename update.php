<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== TRUE) {
    header('Location: login.php');
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$conn = new mysqli('localhost', 'root', '', 'Lab_7');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $role = $_POST['role'];

    $sql = "UPDATE users SET name=?, role=? WHERE matric=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $role, $matric);
    if ($stmt->execute()) {
        echo "Record updated successfully. <a href='display.php'>Go back</a>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $stmt->close();
} else {
    $matric = $_GET['matric'];
    $sql = "SELECT * FROM users WHERE matric=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Update User</title>
    </head>
    <body>
        <h2>Update User</h2>
        <form method="POST" action="update.php">
            Matric: <input type="text" name="matric" value="<?php echo $user['matric']; ?>"required><br>
            Name: <input type="text" name="name" value="<?php echo $user['name']; ?>" required><br>
            Access Level: <input type="text" name="role" value="<?php echo $user['role']; ?>" required><br>
            <input type="submit" value="Update"> <a href="display.php">Cancel</a>
        </form>
        
    </body>
    </html>
    <?php
}

$conn->close();
?>

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

if (isset($_GET['matric'])) {
    $matric = $_GET['matric'];

    $sql = "DELETE FROM users WHERE matric=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matric);
    if ($stmt->execute()) {
        echo "Record deleted successfully. <a href='display.php'>Go back</a>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>

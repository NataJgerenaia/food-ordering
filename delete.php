<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "res";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize inputs
function sanitizeInput($conn, $data) {
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($data)));
}

// Check if delete_id is set through GET method
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delete_id'])) {
    $delete_id = sanitizeInput($conn, $_GET['delete_id']);

    // Delete record from database
    $sql_delete = "DELETE FROM add_food WHERE id = '$delete_id'";

    if ($conn->query($sql_delete) === TRUE) {
        echo "<div class='alert alert-success mt-3'>Food item deleted successfully.</div>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>

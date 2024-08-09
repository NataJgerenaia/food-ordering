<?php
// Database connection parameters
$servername = "localhost";
$username = "your_username";
$password = "your_password";
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

// Insert new food item
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitizeInput($conn, $_POST['name']);
    $price = sanitizeInput($conn, $_POST['price']);
    $picture = sanitizeInput($conn, $_POST['picture']);
    $description = sanitizeInput($conn, $_POST['description']);

    $sql = "INSERT INTO add_food (name, price, picture, description) VALUES ('$name', '$price', '$picture', '$description')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

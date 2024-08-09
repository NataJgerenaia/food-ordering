<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Food Item</title>
    <!-- Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h2 class="mt-4">Edit Food Item</h2>

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

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
        $id = sanitizeInput($conn, $_GET['id']);

        // Fetch food item details
        $sql = "SELECT * FROM add_food WHERE id = '$id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $name = $row['name'];
            $price = $row['price'];
            $description = $row['description'];
            $picture = $row['picture'];
        } else {
            echo "<div class='alert alert-danger mt-3'>Food item not found.</div>";
            exit();
        }
    } else {
        echo "<div class='alert alert-danger mt-3'>Invalid request.</div>";
        exit();
    }

    // Handle form submission to update food item
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = sanitizeInput($conn, $_POST['id']);
        $name = sanitizeInput($conn, $_POST['name']);
        $price = sanitizeInput($conn, $_POST['price']);
        $description = sanitizeInput($conn, $_POST['description']);

        // Update database record
        $sql_update = "UPDATE add_food SET name='$name', price='$price', description='$description' WHERE id='$id'";

        if ($conn->query($sql_update) === TRUE) {
            echo "<div class='alert alert-success mt-3'>Food item updated successfully.</div>";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
    ?>

    <!-- Edit Food Item Form -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" class="mt-4">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" required>
        </div>

        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo $price; ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea class="form-control" id="description" name="description" rows="4" required><?php echo $description; ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Food Item</button>
        <a href="added-food.php" class="btn btn-secondary ml-2">Cancel</a>
    </form>

</div>

<!-- Bootstrap JS for form handling (not necessary for PHP operations) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

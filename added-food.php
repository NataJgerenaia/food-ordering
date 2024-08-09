<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Added Food Items</title>
    <!-- Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .form-popup {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            border: 1px solid #ccc;
            background-color: white;
            padding: 20px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mt-4">Added Food Items</h2>

    <button class="btn btn-primary mt-3" onclick="openForm()">Add Food</button>

    <div class="form-popup" id="myForm">
        <form action="added-food.php" method="post" enctype="multipart/form-data" class="p-4">
            <h2 class="mb-3">Add New Food Item</h2>

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" required>
            </div>

            <div class="form-group">
                <label for="picture">Picture:</label>
                <input type="file" class="form-control-file" id="picture" name="picture" accept="image/*" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Add Food</button>
            <button type="button" class="btn btn-secondary ml-2" onclick="closeForm()">Close</button>
        </form>
    </div>

    <!-- Display added food items -->
    <div class="mt-4">
        <?php
        
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "res";

      
        $conn = new mysqli($servername, $username, $password, $dbname);

     
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        function sanitizeInput($conn, $data) {
            return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($data)));
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = sanitizeInput($conn, $_POST['name']);
            $price = sanitizeInput($conn, $_POST['price']);
            $description = sanitizeInput($conn, $_POST['description']);

            $picture = '';
            if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
                $picture_name = $_FILES['picture']['name'];
                $picture_tmp_name = $_FILES['picture']['tmp_name'];
                $upload_dir = 'uploads/';
                $picture = $upload_dir . $picture_name;
                move_uploaded_file($picture_tmp_name, $picture);
            }

            $sql = "INSERT INTO add_food (name, price, picture, description) VALUES ('$name', '$price', '$picture', '$description')";

            if ($conn->query($sql) === TRUE) {
                echo "<div class='alert alert-success mt-3'>New food item added successfully</div>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        if (isset($_GET['delete_id'])) {
            $delete_id = sanitizeInput($conn, $_GET['delete_id']);
            $sql_delete = "DELETE FROM add_food WHERE id = '$delete_id'";
            if ($conn->query($sql_delete) === TRUE) {
                echo "<div class='alert alert-success mt-3'>Food item deleted successfully</div>";
            } else {
                echo "Error deleting record: " . $conn->error;
            }
        }

        $sql_fetch = "SELECT * FROM add_food";
        $result = $conn->query($sql_fetch);

        if ($result->num_rows > 0) {
            echo "<h3 class='mt-4'>Food Items:</h3>";
            while ($row = $result->fetch_assoc()) {
                echo "<div class='card mb-3'>";
                echo "<div class='card-body'>";
                echo "<h4 class='card-title'>" . $row["name"] . "</h4>";
                echo "<p class='card-text'><strong>Price:</strong> $" . $row["price"] . "</p>";
                echo "<p class='card-text'><strong>Description:</strong> " . $row["description"] . "</p>";
                echo "<img src='" . $row["picture"] . "' class='img-fluid' alt='Food Picture'>";
                echo "<br><br>";
                echo "<a href='edit-food.php?id=" . $row["id"] . "' class='btn btn-primary mr-2'>Edit</a>";
                echo "<a href='added-food.php?delete_id=" . $row["id"] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this item?\")'>Delete</a>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p class='mt-4'>No food items added yet.</p>";
        }

        $conn->close();
        ?>
    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function openForm() {
        document.getElementById("myForm").style.display = "block";
    }

    function closeForm() {
        document.getElementById("myForm").style.display = "none";
    }
</script>

</body>
</html>

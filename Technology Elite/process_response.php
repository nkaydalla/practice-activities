<?php
// Database credentials
$servername = "localhost";
$username = "root";
$password = "Password12345!"; // Default password for local setup; change if necessary
$dbname = "test_db"; // Your database name

// Create connection (without specifying the database initially)
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to create the database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";

// Execute the query to create the database
if ($conn->query($sql) === TRUE) {
    echo "Database '$dbname' created successfully or already exists.<br>";
} else {
    echo "Error creating database: " . $conn->error;
}

// Select the database
$conn->select_db($dbname);

// SQL query to create table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS t_response (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    contact_no VARCHAR(20),
    address TEXT,
    email VARCHAR(255) NOT NULL UNIQUE,
    response_text TEXT NOT NULL
)";

// Execute the query to create the table
if ($conn->query($sql) === TRUE) {
    echo "Table 't_response' created successfully or already exists.<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

// Check if form is submitted via POST
// Check if form is submitted using POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    var_dump($_POST);
    // Collect and sanitize form data
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $contactNo = mysqli_real_escape_string($conn, $_POST['contactNo']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $response = mysqli_real_escape_string($conn, $_POST['response']);

    // Prepare SQL statement to insert data
    $stmt = $conn->prepare("INSERT INTO t_response (first_name, last_name, contact_no, address, email, response_text) 
                            VALUES (?, ?, ?, ?, ?, ?)");

    // Bind parameters
    $stmt->bind_param("ssssss", $firstName, $lastName, $contactNo, $address, $email, $response);

    // Execute the query
    if ($stmt->execute()) {
        echo "New record created successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>
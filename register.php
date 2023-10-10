<?php
// Include the database credentials
require_once 'db_credentials.php';

// Define the validation rules for the form fields
$validation_rules = [
    'name' => '/^[a-zA-Z ]{2,50}$/',
    'email' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
    'phone' => '/^[0-9]{10}$/',
    'address' => '/^[a-zA-Z0-9\s,\'-]{2,100}$/',
    'city' => '/^[a-zA-Z ]{2,50}$/',
    'state' => '/^[a-zA-Z ]{2,50}$/'
];

// Define the error messages for the validation rules
$error_messages = [
    'name' => 'Please enter a valid name (2-50 characters, letters and spaces only)',
    'email' => 'Please enter a valid email address',
    'phone' => 'Please enter a valid phone number (10 digits only)',
    'address' => 'Please enter a valid address (2-100 characters, letters, numbers, spaces, commas, apostrophes and hyphens only)',
    'city' => 'Please enter a valid city (2-50 characters, letters and spaces only)',
    'state' => 'Please enter a valid state (2-50 characters, letters and spaces only)'
];

// Initialize the error messages array
$errors = [];

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the form fields
    foreach ($validation_rules as $field => $rule) {
        if (!preg_match($rule, $_POST[$field])) {
            $errors[$field] = $error_messages[$field];
        }
    }

    // If there are no errors, insert the form data into the database
    if (empty($errors)) {
        try {
            // Connect to the database
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare the SQL statement
            $stmt = $conn->prepare("INSERT INTO clients (name, email, phone, address, city, state) VALUES (:name, :email, :phone, :address, :city, :state)");

            // Bind the parameters
            $stmt->bindParam(':name', $_POST['name']);
            $stmt->bindParam(':email', $_POST['email']);
            $stmt->bindParam(':phone', $_POST['phone']);
            $stmt->bindParam(':address', $_POST['address']);
            $stmt->bindParam(':city', $_POST['city']);
            $stmt->bindParam(':state', $_POST['state']);

            // Execute the statement
            $stmt->execute();

            // Redirect the user to the thank you page
            header("Location: thank-you.html");
            exit;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    }
}
?>

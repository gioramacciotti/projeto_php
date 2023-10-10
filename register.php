/**
 * This script handles the registration form submission and validation.
 * It connects to a MySQL database and inserts the client information if there are no errors.
 *
 * @param array $errors An array to store validation errors.
 * @param string $name The client's name.
 * @param string $email The client's email address.
 * @param string $phone The client's phone number.
 * @param string $address The client's address.
 * @param string $city The client's city.
 * @param string $state The client's state.
 * @param string $cpf The client's CPF (Brazilian ID number).
 */
<?php
$errors = array();

// Create a MySQL connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "local_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty($_POST["name"])) {
        $errors['name'] = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
        // Check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
            $errors['name'] = "Only letters and white space allowed";
        }
    }

    // Validate email
    if (empty($_POST["email"])) {
        $errors['email'] = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        // Check if email address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format";
        }
    }

    // Validate phone
    if (empty($_POST["phone"])) {
        $errors['phone'] = "Phone is required";
    } else {
        $phone = test_input($_POST["phone"]);
        // Check if phone number is valid
        if (!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/",$phone)) {
            $errors['phone'] = "Invalid phone number format. Use XXX-XXX-XXXX";
        }
    }

    // Validate address
    if (empty($_POST["address"])) {
        $errors['address'] = "Address is required";
    } else {
        $address = test_input($_POST["address"]);
    }

    // Validate city
    if (empty($_POST["city"])) {
        $errors['city'] = "City is required";
    } else {
        $city = test_input($_POST["city"]);
    }

    // Validate state
    if (empty($_POST["state"])) {
        $errors['state'] = "State is required";
    } else {
        $state = test_input($_POST["state"]);
    }

    // Validate CPF
    if (empty($_POST["cpf"])) {
        $errors['cpf'] = "CPF is required";
    } else {
        $cpf = test_input($_POST["cpf"]);
        // Check if CPF is valid
        if (!preg_match("/^[0-9]{3}\.[0-9]{3}\.[0-9]{3}-[0-9]{2}$/",$cpf)) {
            $errors['cpf'] = "Invalid CPF format. Use XXX.XXX.XXX-XX";
        }
    }

    // If there are no errors, insert the client information into the "clients" table
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("INSERT INTO clients (name, email, phone, address, city, state, cpf)
            VALUES (:name, :email, :phone, :address, :city, :state, :cpf)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':city', $city);
            $stmt->bindParam(':state', $state);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->execute();
            header("Location: success.php");
            exit();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$conn = null;
?>

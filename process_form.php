<?php
$servername = "localhost";  // or the IP address of your MySQL server
$username = "u184025350_virat_landing";         // your MySQL username
$password = "n2^H#9IM|84&";             // your MySQL password
$dbname = "u184025350_virat_landingg";   // your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Sanitize and validate inputs
    $full_name = htmlspecialchars($full_name);
    $phone_number = htmlspecialchars($phone_number);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($message);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Please enter a valid email address.";
    } else {
        // Save to database
        $stmt = $conn->prepare("INSERT INTO submissions (full_name, phone_number, email, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $full_name, $phone_number, $email, $message);

        if ($stmt->execute()) {
            // Send email
            $to = "ads@thecogent.in"; // Replace with your email address
            $subject = "New Lead Submission";
            $email_message = "You have received a new lead:\n\n";
            $email_message .= "Full Name: " . $full_name . "\n";
            $email_message .= "Phone Number: " . $phone_number . "\n";
            $email_message .= "Email: " . $email . "\n";
            $email_message .= "Message: " . $message . "\n";

            $headers = "From: seo@thecogent.in\r\n"; // Replace with your domain
            $headers .= "Reply-To: " . $email . "\r\n";

            if (mail($to, $subject, $email_message, $headers)) {
                header("Location: thankyou.php");
                exit;
            } else {
                echo "Email could not be sent.";
            }
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
} else {
    echo "Form not submitted correctly.";
}
$conn->close();
?>

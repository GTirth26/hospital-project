<?php
// Aapka connection file include kiya
include 'db.php';

if (isset($_POST['submit'])) {
    // Form se data receive karna
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $date = $_POST['date'];
    $message = $_POST['message'];

    // Data database mein insert karne ki query
    $sql = "INSERT INTO appointments (name, email, phone, appointment_date, message) 
            VALUES ('$name', '$email', '$phone', '$date', '$message')";

    if ($conn->query($sql) === TRUE) {
        // Success message aur wapas home page par bhej dena
        echo "<script>
                alert('Success! Your appointment has been booked.');
                window.location.href='index.html'; 
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
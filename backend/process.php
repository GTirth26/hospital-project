<?php
// Database connection file ko yahan joda
include 'db.php'; 

if(isset($_POST['submit'])) {
    $name = $_POST['patient_name'];
    // Baaki ka data yahan aayega...

    // SQL Query data insert karne ke liye
    $query = "INSERT INTO appointments (name) VALUES ('$name')";
    
    if($conn->query($query)) {
        echo "<script>alert('Appointment Booked!'); window.location.href='final project1.html';</script>";
    }
}
?>
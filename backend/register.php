<?php
include 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $person = $_POST['person'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $history = $_POST['medical-history'];
    $day = $_POST['appointment-day'];
    $time = $_POST['appointment-time'];
    $payment = $_POST['payment_method'];

    $sql = "INSERT INTO patient_registrations (full_name, register_as, gender, dob, phone, address, medical_history, appointment_day, appointment_time, payment_method) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $name, $person, $gender, $dob, $phone, $address, $history, $day, $time, $payment);

    if ($stmt->execute()) {
        echo "success"; 
    } else {
        echo "error: " . $conn->error;
    }
    $stmt->close();
    $conn->close();
}
?>
<?php
error_reporting(0); 
include '../backend/db.php';

// URL से डेटा लेना
$name = isset($_GET['name']) ? mysqli_real_escape_string($conn, $_GET['name']) : '';
$phone = isset($_GET['phone']) ? mysqli_real_escape_string($conn, $_GET['phone']) : '';

if (empty($name) || empty($phone)) {
    die("<div style='text-align:center; padding:50px;'><h2>Invalid Access</h2><p>Please book an appointment first.</p></div>");
}

// सबसे लेटेस्ट डेटा खोजने के लिए SQL
// हमने यहाँ LIKE का इस्तेमाल किया है ताकि नाम मैच होने में दिक्कत न हो
$sql = "SELECT * FROM patient_registrations WHERE full_name LIKE '%$name%' AND phone='$phone' ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    // अगर फिर भी डेटा न मिले, तो नाम के बिना सिर्फ फोन से कोशिश करें
    $sql_retry = "SELECT * FROM patient_registrations WHERE phone='$phone' ORDER BY id DESC LIMIT 1";
    $result_retry = $conn->query($sql_retry);
    if ($result_retry && $result_retry->num_rows > 0) {
        $data = $result_retry->fetch_assoc();
    } else {
        die("<div style='text-align:center; padding:50px;'><h2>Data Not Found</h2><p>Database mein patient '$name' ($phone) ki details nahi mili. <br>Kripya <b>register.php</b> mein database check karein.</p></div>");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Receipt | Majestic Hospital</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .receipt-card { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 500px; border-top: 6px solid #2563eb; }
        .header { text-align: center; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 20px; }
        .header h1 { color: #2563eb; margin: 0; font-size: 24px; text-transform: uppercase; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 15px; border-bottom: 1px dashed #eee; padding-bottom: 5px; }
        .label { color: #64748b; font-weight: 500; }
        .value { color: #1e293b; font-weight: 700; }
        .status-badge { background: #dcfce7; color: #166534; text-align: center; padding: 12px; border-radius: 8px; font-weight: bold; margin-top: 25px; border: 1px solid #bbf7d0; }
        .btn-print { background: #2563eb; color: white; border: none; padding: 15px; width: 100%; border-radius: 8px; cursor: pointer; margin-top: 20px; font-size: 16px; font-weight: 600; }
        @media print { .btn-print { display: none; } }
    </style>
</head>
<body>

<div class="receipt-card">
    <div class="header">
        <h1>Majestic Hospital</h1>
        <p>Appointment Confirmation</p>
    </div>

    <div class="detail-row"><span class="label">Patient Name:</span> <span class="value"><?php echo htmlspecialchars($data['full_name']); ?></span></div>
    <div class="detail-row"><span class="label">Contact:</span> <span class="value"><?php echo $data['phone']; ?></span></div>
    <div class="detail-row"><span class="label">Appointment Day:</span> <span class="value"><?php echo $data['appointment_day']; ?></span></div>
    <div class="detail-row"><span class="label">Time Slot:</span> <span class="value"><?php echo $data['appointment_time']; ?></span></div>
    <div class="detail-row"><span class="label">Payment Mode:</span> <span class="value"><?php echo $data['payment_method']; ?></span></div>

    <div class="status-badge">PAID: ₹500 (SUCCESS)</div>

    <button class="btn-print" onclick="window.print()">Print Receipt / Save PDF</button>
</div>

</body>
</html>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// db.php isi folder (backend) mein hai
include 'db.php'; 

if (!isset($conn) || $conn->connect_error) {
    die("Database connection failed.");
}

// Search Logic
$search_query = "";
if (isset($_GET['search'])) {
    $search_term = $conn->real_escape_string($_GET['search']);
    $search_query = " WHERE full_name LIKE '%$search_term%' OR phone LIKE '%$search_term%' ";
}

// Data fetch with search
$sql = "SELECT * FROM patient_registrations $search_query ORDER BY registration_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Majestic Hospital</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root { 
            --primary: #2563eb; 
            --secondary: #1e293b;
            --bg: #f1f5f9; 
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); padding: 30px; margin: 0; }
        .admin-container { max-width: 1200px; margin: auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        
        .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        
        /* Professional Back Button Styling */
        .btn-portal { 
            text-decoration: none; 
            background: var(--secondary); 
            color: white; 
            padding: 10px 20px; 
            border-radius: 50px; 
            font-size: 14px; 
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-portal:hover {
            background: #000;
            transform: translateX(-5px);
        }

        /* Search Box Styling */
        .search-container { margin-bottom: 20px; display: flex; gap: 10px; }
        .search-input { padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; width: 300px; font-size: 14px; }
        .btn-search { background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600; }
        
        table { width: 100%; border-collapse: collapse; }
        th { background: #f8fafc; color: #64748b; font-size: 0.85rem; text-transform: uppercase; padding: 15px; text-align: left; border-bottom: 2px solid #edf2f7; }
        td { padding: 15px; border-bottom: 1px solid #edf2f7; font-size: 0.95rem; }
        .badge { padding: 4px 10px; border-radius: 50px; font-size: 12px; font-weight: 600; }
        .badge-patient { background: #dcfce7; color: #166534; }
        .badge-guardian { background: #fef9c3; color: #854d0e; }
    </style>
</head>
<body>

<div class="admin-container">
    <div class="header-flex">
        <h2>Patient Registration Records</h2>
        <a href="../Frontend/final project1.HTML" class="btn-portal">← Back to Portal</a>
    </div>

    <form class="search-container" method="GET" action="">
        <input type="text" name="search" class="search-input" placeholder="Search by Name or Phone..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit" class="btn-search">Search</button>
        <?php if(isset($_GET['search'])): ?>
            <a href="admin_patients.php" style="padding:10px; color:red; text-decoration:none; font-size:14px;">Clear Search</a>
        <?php endif; ?>
    </form>

    <table>
        <thead>
            <tr>
                <th>Patient Details</th>
                <th>Type</th>
                <th>Preferred Slot</th>
                <th>Contact</th>
                <th>Registered On</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $type = $row['register_as'] ?? 'patient';
                    $badgeClass = ($type == 'patient') ? 'badge-patient' : 'badge-guardian';
                    
                    $day = $row['appointment_day'] ?? 'N/A';
                    $rawTime = $row['appointment_time'] ?? null;
                    $time = $rawTime ? date('h:i A', strtotime($rawTime)) : 'N/A';

                    echo "<tr>
                            <td><strong>".htmlspecialchars($row['full_name'])."</strong><br><small>DOB: ".$row['dob']."</small></td>
                            <td><span class='badge $badgeClass'>".ucfirst($type)."</span></td>
                            <td><strong>".$day."</strong><br><small>Time: ".$time."</small></td>
                            <td>".$row['phone']."</td>
                            <td>".date('d M Y', strtotime($row['registration_date']))."</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5' style='text-align:center; padding:40px;'>No results found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
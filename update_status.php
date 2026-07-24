<?php
session_start();
include 'db.php';

$admin_emails = [
    'jhsowrov079@gmail.com',
    'fariajarin834@gmail.com',
    'zearin_akhter1014@uits.edu.bd',
    'fatema_akter1015@uits.edu.bd'
];

$current_user = isset($_SESSION['user']['email']) ? trim(strtolower($_SESSION['user']['email'])) : '';

// 1. SECURITY: Verify Admin Session
if (!isset($_SESSION['user']) || !in_array($current_user, $admin_emails)) {
    exit("Unauthorized Action");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    
    // Normalize status and department inputs
    $status = trim($_POST['status'] ?? 'Pending'); 
    $department = trim($_POST['department'] ?? '');

    if ($id > 0) {
        // 2. UPDATE COMPLAINT: Set the new status and department
        $stmt = $conn->prepare("UPDATE complaints SET status = ?, department = ? WHERE id = ?");
        $stmt->bind_param("ssi", $status, $department, $id);

        if ($stmt->execute()) {
            
            // 3. NOTIFICATION LOGIC: Trigger alert if status is "Resolved"
            if ($status === 'Resolved') {
                // First, fetch the user_id and complaint_id associated with this primary 'id'
                $info_stmt = $conn->prepare("SELECT user_id, complaint_id FROM complaints WHERE id = ?");
                $info_stmt->bind_param("i", $id);
                $info_stmt->execute();
                $info_res = $info_stmt->get_result();
                
                if ($info_row = $info_res->fetch_assoc()) {
                    $reporter_user_id = $info_row['user_id'];
                    $complaint_id = $info_row['complaint_id'];
                    $msg = "Your report (ID: $complaint_id) has been resolved. Please share your feedback.";

                    // Insert into the notifications table
                    $notif_stmt = $conn->prepare("INSERT INTO notifications (user_id, complaint_id, message) VALUES (?, ?, ?)");
                    $notif_stmt->bind_param("iss", $reporter_user_id, $complaint_id, $msg);
                    $notif_stmt->execute();
                }
            }

            // Redirect back to admin panel with confirmation
            header("Location: admin.php?msg=Success: Case updated to $status");
            exit();
        } else {
            die("Database Error: " . $stmt->error); 
        }
    }
}
?>
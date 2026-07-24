<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $dept = $_POST['department'];

    $stmt = $conn->prepare("UPDATE complaints SET status = ?, department = ? WHERE id = ?");
    $stmt->bind_param("ssi", $status, $dept, $id);

    if ($stmt->execute()) {
        header("Location: admin.php?msg=Complaint successfully forwarded.");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
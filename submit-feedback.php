<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $complaint_id = $_POST['complaint_id'] ?? '';
    $rating = $_POST['rating'] ?? 0;
    
    // FIX 1: Match the name attribute from your form ("comment" NOT "comments")
    $comment = $_POST['comment'] ?? ''; 

    if (!empty($complaint_id)) {
        // FIX 2: Change "feedbacks" to "feedback" to match your DB schema
        $check_stmt = $conn->prepare("SELECT id FROM feedback WHERE complaint_id = ?");
        $check_stmt->bind_param("s", $complaint_id);
        $check_stmt->execute();
        $res = $check_stmt->get_result();

        if ($res->num_rows == 0) {
            // FIX 3: Ensure INSERT also uses the correct table and column names
            $stmt = $conn->prepare("INSERT INTO feedback (complaint_id, rating, comment) VALUES (?, ?, ?)");
            $stmt->bind_param("sis", $complaint_id, $rating, $comment);
            
            if ($stmt->execute()) {
                header("Location: track.php?id=$complaint_id&msg=Feedback+Submitted");
                exit();
            } else {
                echo "Error submitting feedback: " . $conn->error;
            }
        } else {
            echo "Feedback already exists for this complaint.";
        }
    }
}
?>
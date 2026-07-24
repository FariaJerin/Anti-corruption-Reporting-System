<?php
session_start();
include 'db.php';
include 'header.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$msg = "";

// Ensure user is logged in
if (!isset($_SESSION['user'])) {
    echo "<div class='center'><div class='card'><h3>Please <a href='universal-login.php'>Login</a> to submit a report.</h3></div></div>";
    include 'footer.php';
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $desc = $_POST['desc'] ?? '';
    $cat  = $_POST['category'] ?? 'General';
    $anon = isset($_POST['anon']) ? 1 : 0;
    $uid  = $_SESSION['user']['id'];

    // 1. Handle File Upload
    $filePath = "";
    $is_ai = 0; // Default: Not AI

    if (!empty($_FILES['file']['name'])) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        
        $fileName = time() . "_" . basename($_FILES["file"]["name"]);
        $target = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target)) {
            $filePath = $target;

            // --- SIGHTENGINE API INTEGRATION ---
            // Using the 'media' upload method because localhost is not accessible via public URL
            $api_user = 'YOUR_SIGHTENGINE_USER_ID'; 
            $api_secret = 'YOUR_SIGHTENGINE_API_SECRET';

            $post_data = array(
                'api_user' => $api_user,
                'api_secret' => $api_secret,
                'models' => 'genai,edit',
                'media' => new CURLFile($target) 
            );

            $ch = curl_init('https://api.sightengine.com/1.0/check.json');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            $response = curl_exec($ch);
            curl_close($ch);

            $output = json_decode($response, true);

            // Check if the API successfully flagged GenAI content
            if (isset($output['status']) && $output['status'] == 'success') {
                if ($output['genai']['score'] > 0.70) {
                    $is_ai = 1; // Mark as potential AI content
                }
            }
            // --- END API INTEGRATION ---
        }
    }

    // 2. Insert into Database (Updated to include is_ai_generated)
    $stmt = $conn->prepare("INSERT INTO complaints (user_id, description, category, file_path, status, is_anonymous, is_ai_generated) VALUES (?, ?, ?, ?, 'Pending', ?, ?)");
    $stmt->bind_param("isssii", $uid, $desc, $cat, $filePath, $anon, $is_ai);
    
    if ($stmt->execute()) {
        $last_id = $conn->insert_id; 
        $custom_id = "ACRS" . ($last_id + 100000); 

        // 3. Update the row with the custom ACRS ID
        $update = $conn->prepare("UPDATE complaints SET complaint_id = ? WHERE id = ?");
        $update->bind_param("si", $custom_id, $last_id);
        $update->execute();

        $msg = "<div style='color:#4ade80; padding:10px;'>Submitted! Tracking ID: <b>$custom_id</b></div>";
    } else {
        $msg = "<div style='color:#ef4444;'>Error: " . $conn->error . "</div>";
    }
}
?>

<div class="center">
    <div class="card wide">
        <h2>Submit a Report</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Incident Description</label>
            <textarea name="desc" placeholder="Provide as much detail as possible..." required></textarea>

            <label>Category</label>
            <select name="category">
                <option value="Bribe">Bribe / Extortion</option>
                <option value="Medical">Medical Negligence</option>
                <option value="Education">Education Sector Corruption</option>
                <option value="Transport">Transport / Traffic Issues</option>
                <option value="Food Adulteration">Food Adulteration</option>
                <option value="Harassment">Harassment</option>
                <option value="Other">Other</option>
            </select>

            <label>Attach Evidence (Optional)</label>
            <input type="file" name="file" accept="image/*">
            <p style="font-size: 0.8rem; color: #94a3b8;">Supported: JPG, PNG. Evidence is verified for authenticity.</p>

            <label class="checkbox">
                <input type="checkbox" name="anon"> Submit as Anonymous
            </label>

            <button type="submit" class="btn-primary">Submit Report</button>
        </form>

        <div class="message-box" style="margin-top: 15px; text-align: center;">
            <?= $msg ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<?php
include 'db.php';
include 'header.php';

$msg = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // 1. Collect inputs safely
    $name  = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $raw_password = $_POST['password'] ?? '';

    // 2. CHECK: If password is empty, stop before hashing
    if (empty($raw_password)) {
        $msg = "<span style='color:#f87171;'>Error: Password cannot be empty.</span>";
    } else {
        // 3. HASH: Create the secure version of the password
        $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

        // 4. CHECK EMAIL: Ensure it isn't a duplicate
        $checkEmail = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        $result = $checkEmail->get_result();

        if($result->num_rows > 0) {
            $msg = "<span style='color:#f87171;'>Error: This email is already registered.</span>";
        } else {
            // 5. INSERT: Match the 3 columns (name, email, password) in your 'users' table
            // We use $hashed_password here, which is definitely NOT null now.
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashed_password);

            if($stmt->execute()){
                $msg = "<span style='color:#4ade80;'>Signup Successful! <a href='universal-login.php' style='color:#38bdf8;'>Login here</a></span>";
            } else {
                $msg = "<span style='color:#f87171;'>Database Error: " . $conn->error . "</span>";
            }
        }
    }
}
?>

<div class="center">
    <div class="card">
        <h2>Create Account</h2>
        <form method="POST">
            <label>Full Name</label>
            <input name="name" placeholder="Full Name" required>
            
            <label>Email Address</label>
            <input name="email" type="email" placeholder="Email" required>
            
            <label>I am a:</label>
            <select name="role" id="roleSelector" onchange="toggleFields()" required>
                <option value="citizen">Citizen</option>
                <option value="foreign">Foreigner</option>
            </select>

            <div id="citizenField">
                <label>NID Number</label>
                <input name="nid" placeholder="NID Number">
            </div>
            
            <div id="foreignField" style="display:none;">
                <label>Passport Number</label>
                <input name="passport" placeholder="Passport Number">
                <label>Country</label>
                <input name="country" placeholder="Country">
            </div>

            <label>Password</label>
            <input name="password" type="password" placeholder="Password" required>
            
            <button type="submit" class="btn-primary" style="margin-top: 10px;">Signup</button>
        </form>
        <div style="margin-top: 15px; text-align: center;"><?= $msg ?></div>
    </div>
</div>

<script>
function toggleFields() {
    var role = document.getElementById("roleSelector").value;
    document.getElementById("citizenField").style.display = (role === "citizen") ? "block" : "none";
    document.getElementById("foreignField").style.display = (role === "foreign") ? "block" : "none";
}
</script>

<?php include 'footer.php'; ?>
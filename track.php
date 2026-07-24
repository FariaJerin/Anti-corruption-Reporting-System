<?php
session_start(); 
include 'db.php';
include 'header.php';

$complaint = null;
$error = "";

// --- IMPROVED NOTIFICATION CLEARING LOGIC ---
if (isset($_SESSION['user']) && isset($_GET['id'])) {
    $logged_user_id = $_SESSION['user']['id'];
    $current_track_id = trim($_GET['id']);
    
    // Silently mark the notification as read
    $clear_stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND complaint_id = ?");
    $clear_stmt->bind_param("is", $logged_user_id, $current_track_id);
    $clear_stmt->execute();
}

if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    $search_id = trim($_GET['id']);

    $stmt = $conn->prepare("SELECT * FROM complaints WHERE complaint_id = ?");
    $stmt->bind_param("s", $search_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        $complaint = $row;
    } else {
        $error = "Complaint ID '$search_id' was not found in our records.";
    }
}
?>

<div class="center">
    <div class="card" style="width: 100%; max-width: 700px; margin: 20px auto; background: #1e293b; color: #f8fafc; padding: 25px; border-radius: 12px;">
        <h2 style="border-bottom: 2px solid #3b82f6; display: inline-block; padding-bottom: 5px;">Track Complaint Status</h2>
        <p style="color: #94a3b8; font-size: 0.9em;">Enter your official Tracking ID (e.g., ACRS100001)</p>

        <form method="GET" style="margin-bottom: 25px; display: flex; gap: 10px; align-items: center;">
            <input name="id" type="text" placeholder="ACRS100000" required 
                   value="<?= isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '' ?>"
                   style="padding: 10px; width: 60%; border-radius: 5px; border: 1px solid #334155; background: #0f172a; color: white;">
            
            <button class="btn-primary" style="padding: 10px 20px; cursor: pointer; background: #3b82f6; color: white; border: none; border-radius: 5px; font-weight: bold; font-size: 0.9rem;">
                Track
            </button>
        </form>

        <div class="result-box">
            <?php if ($complaint): ?>
                <?php 
                    $raw_status = trim($complaint['status']);
                    $status_check = strtolower($raw_status);
                    
                    $badgeBg = "#334155"; 
                    $badgeColor = "#94a3b8"; 
                    $display_status = !empty($raw_status) ? $raw_status : "PENDING";

                    if ($status_check == 'resolved') {
                        $badgeBg = "#065f46"; $badgeColor = "#34d399";
                    } elseif ($status_check == 'forwarded') {
                        $badgeBg = "#1e3a8a"; $badgeColor = "#93c5fd";
                    } elseif ($status_check == 'in progress') {
                        $badgeBg = "#78350f"; $badgeColor = "#fbbf24";
                    }
                ?>
                <div style="text-align: left; background: #0f172a; padding: 25px; border-radius: 10px; border-left: 5px solid #3b82f6; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                    <h3 style="margin-top: 0; color: #3b82f6;">File Found: <?= htmlspecialchars($complaint['complaint_id']) ?></h3>
                    
                    <p style="margin: 10px 0;"><strong>Category:</strong> <span style="color: #cbd5e1;"><?= htmlspecialchars($complaint['category']) ?></span></p>

                    <p style="margin: 15px 0;"><strong>Current Status:</strong> 
                        <span style="padding: 4px 12px; border-radius: 12px; background: <?= $badgeBg ?>; color: <?= $badgeColor ?>; border: 1px solid <?= $badgeColor ?>; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; margin-left: 10px;">
                            <?= htmlspecialchars($display_status) ?>
                        </span>
                    </p>

                    <?php if ($status_check === 'forwarded' && !empty($complaint['department'])): ?>
                        <div style="margin: 20px 0; background: rgba(59, 130, 246, 0.1); padding: 15px; border-radius: 8px; border: 1px dashed #3b82f6;">
                            <p style="color: #3b82f6; margin: 0; font-weight: bold;">📢 Update from Administration:</p>
                            <p style="color: #e2e8f0; margin: 8px 0 0 0; font-size: 0.95em; line-height: 1.5;">
                                Your complaint has been officially forwarded to the <strong><?= htmlspecialchars($complaint['department']) ?></strong>. 
                            </p>
                        </div>
                    <?php endif; ?>

                    <!-- FIX: Table name changed to 'feedback' -->
                    <?php if ($status_check === 'resolved'): ?>
                        <?php
                        $stmt_fb = $conn->prepare("SELECT * FROM feedback WHERE complaint_id = ?");
                        $stmt_fb->bind_param("s", $complaint['complaint_id']);
                        $stmt_fb->execute();
                        $fb_res = $stmt_fb->get_result();
                        ?>

                        <div style="margin-top: 25px; background: rgba(52, 211, 153, 0.1); padding: 20px; border-radius: 10px; border: 1px solid #059669;">
                            <h4 style="color: #34d399; margin-top: 0;">🌟 Share Your Feedback</h4>
                            
                            <?php if ($fb_res->num_rows > 0): $fb_data = $fb_res->fetch_assoc(); ?>
                                <p style="color: #94a3b8; font-size: 0.9rem;">Feedback submitted:</p>
                                <p style="font-size: 0.95rem;"><strong>Rating:</strong> <?= $fb_data['rating'] ?>/5 ⭐</p>
                                <p style="font-size: 0.95rem;"><strong>Comments:</strong> <?= htmlspecialchars($fb_data['comment']) ?></p>
                            <?php else: ?>
                                <form action="submit-feedback.php" method="POST">
                                    <input type="hidden" name="complaint_id" value="<?= $complaint['complaint_id'] ?>">
                                    <select name="rating" required style="width: 100%; padding: 8px; background: #0f172a; color: white; border: 1px solid #334155; border-radius: 5px; margin-bottom: 15px;">
                                        <option value="5">5 - Excellent</option>
                                        <option value="4">4 - Good</option>
                                        <option value="3">3 - Average</option>
                                        <option value="2">2 - Poor</option>
                                        <option value="1">1 - Very Bad</option>
                                    </select>
                                    <textarea name="comment" required placeholder="Your comments..." style="width: 100%; height: 80px; padding: 10px; background: #0f172a; color: white; border: 1px solid #334155; border-radius: 5px;"></textarea>
                                    <button type="submit" style="margin-top: 15px; background: #059669; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; width: 100%;">Submit Feedback</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <p style="margin-top: 20px; font-size: 0.8rem; color: #64748b;">
                        <strong>Submitted On:</strong> <?= date("d M Y, h:i A", strtotime($complaint['created_at'])) ?>
                    </p>
                </div>
            <?php elseif ($error): ?>
                <div style="background: rgba(239, 68, 68, 0.1); color: #f87171; padding: 15px; border-radius: 8px; border: 1px solid #ef4444; text-align: center;">
                    ⚠️ <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
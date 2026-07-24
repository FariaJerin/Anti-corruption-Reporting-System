<?php
session_start();
include 'db.php';
include 'header.php';

// 1. SECURITY: Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: universal-login.php");
    exit();
}

$uid = $_SESSION['user']['id'];

// 2. NOTIFICATION LOGIC: Fetch unread notifications for this user
$notif_query = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC");
$notif_query->bind_param("i", $uid);
$notif_query->execute();
$notifications = $notif_query->get_result();

// 3. FETCH DATA: Fetch only this user's complaints
$stmt = $conn->prepare("SELECT * FROM complaints WHERE user_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $uid);
$stmt->execute();
$res = $stmt->get_result();
?>

<div class="table-container">
    <div class="card wide" style="margin: 20px auto; max-width: 1100px; background: #1e293b; color: #f8fafc; padding: 25px; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);">
        
        <!-- NEW NOTIFICATION SECTION -->
        <?php if ($notifications->num_rows > 0): ?>
            <div class="notif-area" style="margin-bottom: 25px;">
                <?php while($n = $notifications->fetch_assoc()): ?>
                    <div style="background: rgba(59, 130, 246, 0.15); border: 1px solid #3b82f6; padding: 15px; border-radius: 8px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; border-left: 5px solid #3b82f6;">
                        <p style="margin: 0; color: #f8fafc; font-size: 0.95rem;">
                            <strong>🔔 Update:</strong> <?= htmlspecialchars($n['message']) ?>
                        </p>
                        <a href="track.php?id=<?= $n['complaint_id'] ?>" 
                           style="background: #3b82f6; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-size: 0.85rem; font-weight: bold; transition: 0.3s;">
                           Review & Feedback
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

        <h2 style="margin-bottom: 10px; border-bottom: 2px solid #3b82f6; display: inline-block; padding-bottom: 5px;">My Submitted Complaints</h2>
        <p>Welcome back, <strong><?= htmlspecialchars($_SESSION['user']['name']) ?></strong>. Below is the history of your reports.</p>

        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr style="background: #0f172a; text-align: left; color: #94a3b8; text-transform: uppercase; font-size: 0.75rem;">
                    <th style="padding: 15px;">Tracking ID</th>
                    <th style="padding: 15px;">Category</th>
                    <th style="padding: 15px;">Assigned Dept</th>
                    <th style="padding: 15px;">Status</th>
                    <th style="padding: 15px;">Date</th>
                    <th style="padding: 15px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($res->num_rows > 0): ?>
                    <?php while($row = $res->fetch_assoc()): ?>
                    <?php 
                        $db_status = trim($row['status']);
                        $status_check = strtolower($db_status);
                        $badgeStyle = "padding: 5px 12px; border-radius: 6px; font-size: 0.7rem; font-weight: bold; text-transform: uppercase; display: inline-block; min-width: 90px; text-align: center; color: white;";
                    ?>
                    <tr style="border-bottom: 1px solid #334155;">
                        <td style="padding: 15px; color: #3b82f6; font-weight: bold;"><?= htmlspecialchars($row['complaint_id']) ?></td>
                        <td style="padding: 15px;"><?= htmlspecialchars($row['category']) ?></td>
                        
                        <td style="padding: 15px;">
                            <?php if (!empty($row['department'])): ?>
                                <span style="color: #38bdf8; font-size: 0.85rem;">🏢 <?= htmlspecialchars($row['department']) ?></span>
                            <?php else: ?>
                                <span style="color: #64748b; font-size: 0.8rem;">Under Review</span>
                            <?php endif; ?>
                        </td>

                        <td style="padding: 15px;">
                            <?php 
                                if ($status_check == 'resolved') {
                                    echo "<span style='background:#065f46; color:#34d399; $badgeStyle'>Resolved</span>";
                                } elseif ($status_check == 'forwarded') {
                                    echo "<span style='background:#1e3a8a; color:#93c5fd; $badgeStyle'>Forwarded</span>";
                                } elseif ($status_check == 'in progress') {
                                    echo "<span style='background:#78350f; color:#fbbf24; $badgeStyle'>In Progress</span>";
                                } else {
                                    echo "<span style='background:#334155; color:#94a3b8; $badgeStyle'>Pending</span>";
                                }
                            ?>
                        </td>

                        <td style="padding: 15px; font-size: 0.85rem;"><?= date("d M Y", strtotime($row['created_at'])) ?></td>
                        
                        <td style="padding: 15px;">
                            <a href="track.php?id=<?= $row['complaint_id'] ?>" style="background: #3b82f6; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 0.8rem; font-weight: bold;">View Full Track</a>
                        </td>
                    </tr>

                    <?php if ($status_check === 'forwarded' && !empty($row['department'])): ?>
                    <tr style="background: rgba(56, 189, 248, 0.05);">
                        <td colspan="6" style="padding: 10px 15px; border-bottom: 1px solid #334155;">
                            <small style="color: #38bdf8; font-weight: 500;">
                                ℹ️ <strong>Update:</strong> Your complaint has been officially forwarded to the <b><?= htmlspecialchars($row['department']) ?></b> for deeper investigation.
                            </small>
                        </td>
                    </tr>
                    <?php endif; ?>

                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 50px; color: #64748b;">
                            <div style="font-size: 2rem; margin-bottom: 10px;">📋</div>
                            You haven't submitted any complaints yet.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
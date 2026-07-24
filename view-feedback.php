<?php
session_start();
include 'db.php';
include 'header.php';

// Security Check: Only allow Admins
if (!isset($_SESSION['user']) || strtolower($_SESSION['user']['role']) !== 'admin') {
    die("<div class='center'><h2>Access Denied. Admins Only.</h2></div>");
}

// Fetch feedback joined with complaint details
$query = "SELECT f.*, c.category, u.name as user_name 
          FROM feedback f 
          JOIN complaints c ON f.complaint_id = c.complaint_id 
          JOIN users u ON c.user_id = u.id 
          ORDER BY f.submitted_at DESC";

$result = $conn->query($query);
?>

<div class="center" style="max-width: 1000px; margin: 20px auto;">
    <div class="card" style="background: #1e293b; color: white; padding: 20px;">
        <h2 style="border-bottom: 2px solid #3b82f6; padding-bottom: 10px;">User Feedback & Ratings</h2>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px; text-align: left;">
            <thead>
                <tr style="background: #0f172a; color: #3b82f6;">
                    <th style="padding: 12px; border: 1px solid #334155;">ID</th>
                    <th style="padding: 12px; border: 1px solid #334155;">User</th>
                    <th style="padding: 12px; border: 1px solid #334155;">Category</th>
                    <th style="padding: 12px; border: 1px solid #334155;">Rating</th>
                    <th style="padding: 12px; border: 1px solid #334155;">Comment</th>
                    <th style="padding: 12px; border: 1px solid #334155;">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr style="border-bottom: 1px solid #334155;">
                        <td style="padding: 12px;"><?= $row['complaint_id'] ?></td>
                        <td style="padding: 12px;"><?= htmlspecialchars($row['user_name']) ?></td>
                        <td style="padding: 12px;"><?= htmlspecialchars($row['category']) ?></td>
                        <td style="padding: 12px;">
                            <span style="color: #fbbf24; font-weight: bold;">
                                <?= $row['rating'] ?> / 5 ⭐
                            </span>
                        </td>
                        <td style="padding: 12px; font-style: italic;">
                            "<?= htmlspecialchars($row['comment']) ?>"
                        </td>
                        <td style="padding: 12px; font-size: 0.85em; color: #94a3b8;">
                            <?= date("d M Y", strtotime($row['submitted_at'])) ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                
                <?php if($result->num_rows == 0): ?>
                    <tr>
                        <td colspan="6" style="padding: 20px; text-align: center; color: #94a3b8;">
                            No feedback submitted yet.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div style="margin-top: 20px;">
            <a href="admin.php" class="btn-primary" style="text-decoration: none; padding: 10px 20px;">Back to Dashboard</a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
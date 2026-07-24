<?php
session_start();
include 'db.php';
include 'header.php';

// 1. AUTHORIZED ADMIN EMAILS (Fallback)
$admin_emails = ['jhsowrov079@gmail.com', 'fariajarin834@gmail.com', 'zearin_akhter1014@uits.edu.bd', 'fatema_akter1015@uits.edu.bd'];

$current_user_email = isset($_SESSION['user']['email']) ? trim(strtolower($_SESSION['user']['email'])) : '';
$current_user_role  = isset($_SESSION['user']['role']) ? trim(strtolower($_SESSION['user']['role'])) : '';

// 2. STRICT SECURITY CHECK
if (!isset($_SESSION['user']) || ($current_user_role !== 'admin' && !in_array($current_user_email, $admin_emails))) {
    header("Location: index.php"); 
    exit("Access Denied.");
}

// 3. FETCH COMPLAINTS
$query = "SELECT c.*, u.name as reporter_name FROM complaints c LEFT JOIN users u ON c.user_id = u.id ORDER BY c.id DESC";
$res = $conn->query($query);
?>

<style>
    .admin-container { max-width: 1300px; margin: 20px auto; padding: 20px; font-family: 'Segoe UI', system-ui, sans-serif; }
    .stat-card { background: #1e293b; border-radius: 12px; padding: 25px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.3); border: 1px solid #334155; }
    .status-pill { padding: 4px 12px; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
    .btn-feedback { background: linear-gradient(135deg, #8b5cf6, #6d28d9); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold; transition: 0.3s; font-size: 14px; }
    .btn-feedback:hover { opacity: 0.9; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4); }
    
    table { width: 100%; border-collapse: separate; border-spacing: 0 12px; }
    th { color: #94a3b8; text-transform: uppercase; font-size: 11px; padding: 10px 15px; letter-spacing: 1px; }
    tr.data-row { background: #0f172a; transition: 0.3s; }
    tr.data-row:hover { background: #1e293b; transform: scale(1.002); }
    td { padding: 18px 15px; border-top: 1px solid #334155; border-bottom: 1px solid #334155; vertical-align: middle; }
    td:first-child { border-left: 1px solid #334155; border-radius: 12px 0 0 12px; }
    td:last-child { border-right: 1px solid #334155; border-radius: 0 12px 12px 0; }
    
    select, button.update-btn { width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #334155; background: #1e293b; color: #f1f5f9; cursor: pointer; font-size: 13px; outline: none; }
    select:focus { border-color: #38bdf8; }
    button.update-btn { background: #38bdf8; color: #0f172a; font-weight: 800; margin-top: 8px; border: none; transition: 0.2s; text-transform: uppercase; }
    button.update-btn:hover { background: #7dd3fc; filter: brightness(1.1); }
    
    .evidence-link { color: #38bdf8; text-decoration: none; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; }
    .evidence-link:hover { text-decoration: underline; }
    optgroup { background: #0f172a; color: #38bdf8; font-style: normal; }
</style>

<div class="admin-container">
    <div class="stat-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1 style="margin: 0; font-size: 26px; color: #f8fafc;">Control Center</h1>
                <p style="color: #94a3b8; margin: 5px 0; font-size: 14px;">Total Corruption Reports Under Review</p>
            </div>
            <div style="display: flex; gap: 20px; align-items: center;">
                <a href="view-feedback.php" class="btn-feedback">⭐ View Ratings & Feedback</a>
                <div style="text-align: right; border-left: 2px solid #334155; padding-left: 20px;">
                    <span style="display: block; font-size: 11px; color: #94a3b8; text-transform: uppercase;">Authenticated Admin</span>
                    <span style="color: #38bdf8; font-weight: 700; font-size: 14px;"><?= htmlspecialchars($current_user_email) ?></span>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Tracking ID</th>
                    <th>Reporter Details</th>
                    <th>Evidence Analysis</th>
                    <th>Dept & Current Status</th>
                    <th style="width: 240px;">Management Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $res->fetch_assoc()): ?>
                <tr class="data-row">
                    <td><span style="color: #38bdf8; font-family: 'Courier New', monospace; font-size: 16px; font-weight: bold;">#<?= $row['complaint_id'] ?></span></td>
                    
                    <td>
                        <div style="font-weight: 600; color: #f1f5f9; font-size: 15px;"><?= htmlspecialchars($row['reporter_name'] ?? 'Guest User') ?></div>
                        <?php if(isset($row['is_anonymous']) && $row['is_anonymous']): ?>
                            <span style="color: #fbbf24; font-size: 10px; font-weight: 700;">🕵️ ANONYMOUS FILING</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if(!empty($row['file_path'])): ?>
                            <a href="<?= $row['file_path'] ?>" target="_blank" class="evidence-link">📂 View Evidence File</a>
                            <div style="margin-top: 8px; font-size: 10px; font-weight: 900; letter-spacing: 0.5px; color: <?= ($row['is_ai_generated'] ? '#f87171' : '#4ade80') ?>;">
                                <?= ($row['is_ai_generated'] ? '⚠️ AI/EDITED DETECTED' : '✅ AUTHENTICITY VERIFIED') ?>
                            </div>
                        <?php else: ?>
                            <span style="color: #475569; font-size: 13px; font-style: italic;">No Attachments</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <div style="font-size: 13px; font-weight: 600; color: #cbd5e1; margin-bottom: 8px;">🏢 <?= $row['department'] ?: 'Awaiting Assignment' ?></div>
                        <?php 
                            $s = strtolower(trim($row['status']));
                            // Enhanced Status colors
                            if ($s == 'resolved') { $color = "#34d399"; $bg = "rgba(52,211,153,0.15)"; }
                            elseif ($s == 'in progress') { $color = "#fbbf24"; $bg = "rgba(251,191,36,0.15)"; }
                            elseif ($s == 'forwarded') { $color = "#38bdf8"; $bg = "rgba(56,189,248,0.15)"; } // Blue
                            else { $color = "#f87171"; $bg = "rgba(248,113,113,0.15)"; } // Red for Pending
                        ?>
                        <span class="status-pill" style="color: <?= $color ?>; background: <?= $bg ?>; border: 1px solid <?= $color ?>;">
                            <?= strtoupper($row['status'] ?: 'Pending') ?>
                        </span>
                    </td>

                    <td>
                        <form action="update_status.php" method="POST">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            
                            <select name="status" style="margin-bottom: 8px;">
                                <option value="Pending" <?= $row['status']=='Pending'?'selected':'' ?>>Pending</option>
                                <option value="Forwarded" <?= $row['status']=='Forwarded'?'selected':'' ?>>Forwarded</option>
                                <option value="In Progress" <?= $row['status']=='In Progress'?'selected':'' ?>>In Progress</option>
                                <option value="Resolved" <?= $row['status']=='Resolved'?'selected':'' ?>>Resolved</option>
                            </select>

                            <select name="department">
                                <option value="">-- Assign Agency --</option>
                                <optgroup label="Security & Law Enforcement">
                                    <option value="Police HQ" <?= $row['department']=='Police HQ'?'selected':'' ?>>Police Headquarters</option>
                                    <option value="Anti-Corruption Dept" <?= $row['department']=='Anti-Corruption Dept'?'selected':'' ?>>Anti-Corruption Commission</option>
                                    <option value="Intelligence Bureau" <?= $row['department']=='Intelligence Bureau'?'selected':'' ?>>Intelligence Bureau</option>
                                </optgroup>
                                <optgroup label="Finance & Internal Audit">
                                    <option value="Finance Ministry" <?= $row['department']=='Finance Ministry'?'selected':'' ?>>Ministry of Finance</option>
                                    <option value="Tax & VAT Customs" <?= $row['department']=='Tax & VAT Customs'?'selected':'' ?>>Tax & VAT Customs</option>
                                    <option value="Consumer Rights" <?= $row['department']=='Consumer Rights'?'selected':'' ?>>Consumer Rights Authority</option>
                                </optgroup>
                                <optgroup label="Public Services & Infrastructure">
                                    <option value="Health Ministry" <?= $row['department']=='Health Ministry'?'selected':'' ?>>Health & Family Welfare</option>
                                    <option value="Transport/BRTA" <?= $row['department']=='Transport/BRTA'?'selected':'' ?>>Transport (BRTA)</option>
                                    <option value="Land Records" <?= $row['department']=='Land Records'?'selected':'' ?>>Land & Land Records</option>
                                    <option value="City Corporation" <?= $row['department']=='City Corporation'?'selected':'' ?>>City Corporation / LG</option>
                                </optgroup>
                            </select>

                            <button class="update-btn">Apply Update</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
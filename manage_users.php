<?php
session_start();
require 'includes/db.php';
require 'includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') { header("Location: login.php"); exit; }

// DELETE USER LOGIC
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user_id'])) {
    $del_id = $_POST['delete_user_id'];
    if ($del_id != $_SESSION['user_id']) {
        $pdo->prepare("DELETE FROM users WHERE user_id = ?")->execute([$del_id]);
        $msg = "User deleted.";
    }
}

// FETCH USERS
$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Manage Users</h2>
        <a href="admin_dashboard.php" class="btn btn-outline-secondary rounded-pill">Back to Dashboard</a>
    </div>

    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">User</th>
                            <th>Role</th>
                            <th>Qualification</th> <th>Email</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $user): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo htmlspecialchars($user['profile_image'] ?? 'uploads/default.png'); ?>" class="rounded-circle me-2 border" width="35" height="35">
                                        <span class="fw-bold"><?php echo htmlspecialchars($user['full_name']); ?></span>
                                    </div>
                                </td>
                                <td><span class="badge bg-secondary rounded-pill"><?php echo ucfirst($user['role']); ?></span></td>
                                
                                <td class="text-primary">
                                    <?php echo htmlspecialchars($user['qualification'] ?? 'N/A'); ?>
                                </td>
                                
                                <td class="text-muted small"><?php echo htmlspecialchars($user['email']); ?></td>
                                <td class="text-end pe-4">
                                    <?php if($user['role'] != 'admin'): ?>
                                        <form method="POST" onsubmit="return confirm('Delete user?');">
                                            <input type="hidden" name="delete_user_id" value="<?php echo $user['user_id']; ?>">
                                            <button class="btn btn-outline-danger btn-sm rounded-pill px-3">Delete</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
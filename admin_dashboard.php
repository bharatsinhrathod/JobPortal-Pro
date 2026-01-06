<?php
session_start();
require 'includes/db.php';
require 'includes/header.php';

// 1. SECURITY: Only Admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// 2. FETCH TOTAL STATS
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_jobs  = $pdo->query("SELECT COUNT(*) FROM jobs")->fetchColumn();
$total_apps  = $pdo->query("SELECT COUNT(*) FROM applications")->fetchColumn();

// 3. FETCH "HIRED" STAT (Users Accepted)
$hired_users = $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'Accepted'")->fetchColumn();

// 4. FETCH RECENT ACTIVITY FEED (Last 10 Applications)
// This shows "Who applied to What" recently
$recent_apps_sql = "SELECT users.full_name, jobs.title, jobs.company_name, applications.applied_at 
                    FROM applications 
                    JOIN users ON applications.user_id = users.user_id 
                    JOIN jobs ON applications.job_id = jobs.job_id 
                    ORDER BY applications.applied_at DESC LIMIT 10";
$recent_apps = $pdo->query($recent_apps_sql)->fetchAll();
?>

<div class="container py-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-danger">Admin Overview</h2>
            <p class="text-muted">Welcome back, Admin</p>
        </div>
        
        <div>
            <a href="manage_users.php" class="btn btn-dark rounded-pill px-4 shadow-sm me-2">👤 Manage Users</a>
            <a href="manage_jobs.php" class="btn btn-dark rounded-pill px-4 shadow-sm">💼 Manage Jobs</a>
        </div>
    </div>

    <div class="row mb-5 g-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white h-100">
                <div class="card-body p-4 text-center">
                    <h2 class="fw-bold display-4 mb-0"><?php echo $total_users; ?></h2>
                    <div class="text-white-50 text-uppercase fw-bold small">Total Users</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white h-100">
                <div class="card-body p-4 text-center">
                    <h2 class="fw-bold display-4 mb-0"><?php echo $hired_users; ?></h2>
                    <div class="text-white-50 text-uppercase fw-bold small">Hired (Accepted)</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-dark h-100">
                <div class="card-body p-4 text-center">
                    <h2 class="fw-bold display-4 mb-0"><?php echo $total_apps; ?></h2>
                    <div class="text-dark-50 text-uppercase fw-bold small">Total Applications</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-secondary text-white h-100">
                <div class="card-body p-4 text-center">
                    <h2 class="fw-bold display-4 mb-0"><?php echo $total_jobs; ?></h2>
                    <div class="text-white-50 text-uppercase fw-bold small">Active Jobs</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">📢 Recent Application Activity</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Seeker Name</th>
                            <th>Applied For Job</th>
                            <th>Company</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recent_apps as $app): ?>
                            <tr>
                                <td class="ps-4 fw-bold"><?php echo htmlspecialchars($app['full_name']); ?></td>
                                <td class="text-primary fw-bold"><?php echo htmlspecialchars($app['title']); ?></td>
                                <td class="text-muted"><?php echo htmlspecialchars($app['company_name']); ?></td>
                                <td class="text-muted small"><?php echo date('M d, H:i', strtotime($app['applied_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if(count($recent_apps) == 0): ?>
                            <tr><td colspan="4" class="text-center py-5 text-muted">No recent applications found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
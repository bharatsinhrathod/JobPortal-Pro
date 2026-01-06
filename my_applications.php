<?php
// my_applications.php
session_start();
require 'includes/db.php';
require 'includes/header.php';

// 1. Security: Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$applicant_id = $_SESSION['user_id'];

// 2. Fetch Data: Join Applications with Jobs to get the Title and Location
// We select 'applied_at' to show the date
$sql = "SELECT jobs.title, jobs.location, applications.status, applications.applied_at 
        FROM applications 
        JOIN jobs ON applications.job_id = jobs.job_id 
        WHERE applications.applicant_id = ? 
        ORDER BY applications.applied_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$applicant_id]);
$my_apps = $stmt->fetchAll();
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <h2 class="mb-4">My Application History</h2>
            
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Job Title</th>
                                <th>Location</th>
                                <th>Date Applied</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($my_apps) > 0): ?>
                                <?php foreach ($my_apps as $app): ?>
                                <tr>
                                    <td class="fw-bold text-primary">
                                        <?php echo htmlspecialchars($app['title']); ?>
                                    </td>
                                    
                                    <td>
                                        📍 <?php echo htmlspecialchars($app['location']); ?>
                                    </td>
                                    
                                    <td>
                                        <?php echo date("M d, Y", strtotime($app['applied_at'])); ?>
                                    </td>
                                    
                                    <td>
                                        <?php 
                                            if($app['status'] == 'accepted') {
                                                echo '<span class="badge bg-success rounded-pill">Accepted 🎉</span>';
                                            } elseif($app['status'] == 'rejected') {
                                                echo '<span class="badge bg-danger rounded-pill">Rejected</span>';
                                            } else {
                                                echo '<span class="badge bg-warning text-dark rounded-pill">Pending...</span>';
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        You haven't applied to any jobs yet. <a href="index.php">Find a job now!</a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-3">
                <a href="index.php" class="btn btn-outline-secondary">← Back to Job Search</a>
            </div>
            
        </div>
    </div>
</div>

<?php 
// Close the HTML tags opened in header.php
?>
</div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
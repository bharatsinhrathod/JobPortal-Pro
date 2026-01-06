<?php
session_start();
require 'includes/db.php';
require 'includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') { header("Location: login.php"); exit; }

// DELETE JOB LOGIC
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_job_id'])) {
    $pdo->prepare("DELETE FROM jobs WHERE job_id = ?")->execute([$_POST['delete_job_id']]);
}

// FETCH JOBS WITH APPLICANT COUNT
// Uses LEFT JOIN and GROUP BY to count applications per job
$sql = "SELECT jobs.*, users.full_name as recruiter_name, COUNT(applications.application_id) as applicant_count 
        FROM jobs 
        JOIN users ON jobs.recruiter_id = users.user_id 
        LEFT JOIN applications ON jobs.job_id = applications.job_id
        GROUP BY jobs.job_id
        ORDER BY jobs.created_at DESC";
$jobs = $pdo->query($sql)->fetchAll();
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Manage Jobs</h2>
        <a href="admin_dashboard.php" class="btn btn-outline-secondary rounded-pill">Back to Dashboard</a>
    </div>

    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Job Title</th>
                            <th>Company</th>
                            <th>Applicants</th> <th>Recruiter</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($jobs as $job): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-primary"><?php echo htmlspecialchars($job['title']); ?></td>
                                <td><?php echo htmlspecialchars($job['company_name']); ?></td>
                                
                                <td>
                                    <span class="badge bg-info text-dark rounded-pill px-3">
                                        <?php echo $job['applicant_count']; ?> Seekers
                                    </span>
                                </td>

                                <td class="text-muted small"><?php echo htmlspecialchars($job['recruiter_name']); ?></td>
                                <td class="text-end pe-4">
                                    <form method="POST" onsubmit="return confirm('Delete this job?');">
                                        <input type="hidden" name="delete_job_id" value="<?php echo $job['job_id']; ?>">
                                        <button class="btn btn-outline-danger btn-sm rounded-pill px-3">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
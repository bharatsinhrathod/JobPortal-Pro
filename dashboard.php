<?php
session_start();
require 'includes/db.php';
require 'includes/header.php';

// 1. SECURITY: Only Recruiters
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'recruiter') {
    header("Location: login.php");
    exit;
}

$recruiter_id = $_SESSION['user_id'];
$msg = "";
$error = "";

// 2. ACTION: DELETE A JOB
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_job_id'])) {
    $del_id = $_POST['delete_job_id'];
    $del_stmt = $pdo->prepare("DELETE FROM jobs WHERE job_id = ? AND recruiter_id = ?");
    if ($del_stmt->execute([$del_id, $recruiter_id])) {
        $msg = "Job post deleted successfully.";
    }
}

// 3. ACTION: UPDATE APPLICANT STATUS
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['application_id'])) {
    $app_id = $_POST['application_id'];
    $new_status = $_POST['status']; 
    $update_stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE application_id = ?");
    if ($update_stmt->execute([$new_status, $app_id])) {
        $msg = "Candidate status updated.";
    }
}

// 4. FETCH STATS
$total_applicants = $pdo->prepare("SELECT COUNT(*) FROM applications JOIN jobs ON applications.job_id = jobs.job_id WHERE jobs.recruiter_id = ?");
$total_applicants->execute([$recruiter_id]);
$total_applicants = $total_applicants->fetchColumn();

$total_jobs_posted = $pdo->prepare("SELECT COUNT(*) FROM jobs WHERE recruiter_id = ?");
$total_jobs_posted->execute([$recruiter_id]);
$total_jobs_posted = $total_jobs_posted->fetchColumn();

// 5. FETCH DATA LISTS
$my_jobs = $pdo->prepare("SELECT * FROM jobs WHERE recruiter_id = ? ORDER BY created_at DESC");
$my_jobs->execute([$recruiter_id]);
$my_jobs = $my_jobs->fetchAll();

$sql = "SELECT applications.application_id, applications.status, applications.resume, 
               jobs.title as job_title,
               users.full_name, users.email, users.profile_image 
        FROM applications
        JOIN jobs ON applications.job_id = jobs.job_id
        JOIN users ON applications.user_id = users.user_id
        WHERE jobs.recruiter_id = ?
        ORDER BY applications.applied_at DESC";
$applicants = $pdo->prepare($sql);
$applicants->execute([$recruiter_id]);
$applicants = $applicants->fetchAll();
?>

<div class="container py-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">Recruiter Dashboard</h2>
            <p class="text-muted">Manage your job postings and candidates</p>
        </div>
        <a href="post_job.php" class="btn btn-primary rounded-pill px-4 shadow-sm">+ Post New Job</a>
    </div>

    <?php if($msg): ?>
        <div class="alert alert-success rounded-pill text-center"><?php echo $msg; ?></div>
    <?php endif; ?>

    <div class="row mb-5 g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-primary text-white h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div><h6 class="text-white-50 text-uppercase">Jobs Posted</h6><h2 class="fw-bold display-4 mb-0"><?php echo $total_jobs_posted; ?></h2></div>
                    <div class="fs-1">💼</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-dark text-white h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div><h6 class="text-white-50 text-uppercase">Total Applicants</h6><h2 class="fw-bold display-4 mb-0"><?php echo $total_applicants; ?></h2></div>
                    <div class="fs-1">👥</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-lg rounded-4 mb-5">
        <div class="card-header bg-white py-3"><h5 class="fw-bold mb-0">My Posted Jobs</h5></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th class="ps-4">Job Title</th><th>Location</th><th>Posted Date</th><th class="text-end pe-4">Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($my_jobs as $job): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-primary"><?php echo htmlspecialchars($job['title']); ?></td>
                                <td class="text-muted"><?php echo htmlspecialchars($job['location']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($job['created_at'])); ?></td>
                                <td class="text-end pe-4">
                                    <form method="POST" onsubmit="return confirm('Are you sure?');">
                                        <input type="hidden" name="delete_job_id" value="<?php echo $job['job_id']; ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">🗑 Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if(count($my_jobs) == 0): ?><tr><td colspan="4" class="text-center py-4 text-muted">No jobs posted.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-header bg-white py-3"><h5 class="fw-bold mb-0">Manage Candidates</h5></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th class="ps-4">Candidate</th><th>Applied For</th><th>Resume</th><th>Status</th><th class="text-end pe-4">Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($applicants as $row): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo htmlspecialchars($row['profile_image'] ?? 'uploads/default.png'); ?>" class="rounded-circle me-3 border" style="width: 50px; height: 50px; object-fit: cover;">
                                        <div>
                                            <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['full_name']); ?></div>
                                            <a href="mailto:<?php echo $row['email']; ?>?subject=Interview for <?php echo urlencode($row['job_title']); ?>" class="text-decoration-none small">
                                                ✉️ <?php echo htmlspecialchars($row['email']); ?>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-bold text-primary"><?php echo htmlspecialchars($row['job_title']); ?></td>
                                <td>
                                    <?php if(!empty($row['resume'])): ?>
                                        <a href="<?php echo htmlspecialchars($row['resume']); ?>" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill">📄 Resume</a>
                                    <?php else: ?><span class="text-muted small">No Resume</span><?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                        $st = $row['status'];
                                        $badge = ($st == 'Accepted') ? 'bg-success' : (($st == 'Rejected') ? 'bg-danger' : 'bg-warning text-dark');
                                    ?>
                                    <span class="badge <?php echo $badge; ?> rounded-pill px-3"><?php echo $st; ?></span>
                                </td>
                                <td class="text-end pe-4">
                                    <?php if($row['status'] == 'Pending'): ?>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="application_id" value="<?php echo $row['application_id']; ?>">
                                            <button type="submit" name="status" value="Accepted" class="btn btn-success btn-sm rounded-pill px-3 me-1">✓</button>
                                            <button type="submit" name="status" value="Rejected" class="btn btn-outline-danger btn-sm rounded-pill px-3">✕</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted small">Decided</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if(count($applicants) == 0): ?><tr><td colspan="5" class="text-center py-5 text-muted">No applicants.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
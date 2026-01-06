<?php
session_start();
require 'includes/db.php';
require 'includes/header.php';

// 1. SECURITY: Only Applicants
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'applicant') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['job_id'])) {
    header("Location: jobs.php");
    exit;
}

$job_id = $_GET['job_id'];
$user_id = $_SESSION['user_id'];
$msg = "";
$error = "";

// 2. FETCH JOB DETAILS
$stmt = $pdo->prepare("SELECT * FROM jobs WHERE job_id = ?");
$stmt->execute([$job_id]);
$job = $stmt->fetch();

if (!$job) {
    echo "<div class='container py-5'><div class='alert alert-danger'>Job not found.</div></div>";
    require 'includes/footer.php'; // Optional if you have a footer
    exit;
}

// 3. CHECK IF ALREADY APPLIED
$check_stmt = $pdo->prepare("SELECT * FROM applications WHERE job_id = ? AND user_id = ?");
$check_stmt->execute([$job_id, $user_id]);
$already_applied = $check_stmt->fetch();

// 4. HANDLE APPLICATION SUBMISSION
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$already_applied) {
    
    // Resume Upload Logic
    $resume_path = ""; 
    if (!empty($_FILES['resume']['name'])) {
        $target_dir = "uploads/resumes/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true); // Create folder if missing
        
        $target_file = $target_dir . time() . "_" . basename($_FILES["resume"]["name"]);
        if (move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
            $resume_path = $target_file;
        }
    }

    $sql = "INSERT INTO applications (job_id, user_id, resume, status) VALUES (?, ?, ?, 'Pending')";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$job_id, $user_id, $resume_path])) {
        $msg = "Application submitted successfully!";
        $already_applied = true; // Hide the form immediately
    } else {
        $error = "Something went wrong. Please try again.";
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-body p-5">
                    
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-primary"><?php echo htmlspecialchars($job['title']); ?></h2>
                        <h5 class="text-muted fw-bold"><?php echo htmlspecialchars($job['company_name']); ?></h5>
                    </div>
                    
                    <hr>

                    <div class="row text-center mb-4">
                        <div class="col-6 mb-3">
                            <small class="text-uppercase text-muted fw-bold">Location</small>
                            <p class="fw-bold"><?php echo htmlspecialchars($job['location']); ?></p>
                        </div>
                        <div class="col-6 mb-3">
                            <small class="text-uppercase text-muted fw-bold">Salary</small>
                            <p class="fw-bold text-success"><?php echo htmlspecialchars($job['salary_range'] ?? 'Not Disclosed'); ?></p>
                        </div>
                        <div class="col-6">
                            <small class="text-uppercase text-muted fw-bold">Posted On</small>
                            <p><?php echo date('M d, Y', strtotime($job['created_at'])); ?></p>
                        </div>
                        <div class="col-6">
                            <small class="text-uppercase text-muted fw-bold">Job Type</small>
                            <p>Full Time</p>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-5">
                        <h5 class="fw-bold">Job Description</h5>
                        <p class="text-secondary" style="line-height: 1.8;">
                            <?php echo $job['description']; ?>
                        </p>
                    </div>

                    <?php if ($already_applied): ?>
                        
                        <div class="alert alert-success text-center rounded-pill py-3">
                            <h5 class="mb-0">✅ You have applied for this job!</h5>
                        </div>
                        <div class="text-center mt-3">
                            <a href="seeker_dashboard.php" class="btn btn-outline-primary rounded-pill px-4">Go to Dashboard</a>
                        </div>

                    <?php else: ?>

                        <div class="bg-light p-4 rounded-4 border">
                            <h5 class="fw-bold mb-3">Apply Now</h5>
                            
                            <?php if($error): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>

                            <form method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Upload Your Resume (PDF/Doc)</label>
                                    <input type="file" name="resume" class="form-control" required>
                                    <div class="form-text">Please upload a tailored resume for this specific role.</div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm">
                                        🚀 Submit Application
                                    </button>
                                    <a href="jobs.php" class="btn btn-link text-muted">Cancel</a>
                                </div>
                            </form>
                        </div>

                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
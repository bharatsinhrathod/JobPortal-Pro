<?php
// post_job.php
session_start();
require 'includes/db.php';
require 'includes/header.php';

// Check if Recruiter
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'recruiter') {
    header("Location: login.php");
    exit;
}

// Pre-fill company name from user profile if available
$recruiter_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT company_name FROM users WHERE user_id = ?");
$stmt->execute([$recruiter_id]);
$user_company = $stmt->fetchColumn();

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $salary = $_POST['salary_range'];
    $company = $_POST['company_name']; // Get from form input

    if (empty($title) || empty($description) || empty($location) || empty($company)) {
        $error = "Please fill in all required fields.";
    } else {
        $sql = "INSERT INTO jobs (title, description, location, salary_range, company_name, recruiter_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$title, $description, $location, $salary, $company, $recruiter_id])) {
            $success = "Job Posted Successfully!";
        } else {
            $error = "Failed to post job.";
        }
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="fw-bold text-dark">Post a New Job</h2>
                        <a href="dashboard.php" class="btn btn-light rounded-pill border">Cancel</a>
                    </div>

                    <?php if($error): ?>
                        <div class="alert alert-danger rounded-3 text-center"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if($success): ?>
                        <div class="alert alert-success rounded-3 text-center">
                            <?php echo $success; ?> <a href="dashboard.php" class="fw-bold">Go to Dashboard</a>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="title" name="title" placeholder="e.g. Senior Developer" required>
                                    <label for="title">Job Title *</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="company" name="company_name" 
                                           placeholder="Company" value="<?php echo htmlspecialchars($user_company ?? ''); ?>" required>
                                    <label for="company">Company Name *</label>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="location" name="location" placeholder="City" required>
                                    <label for="location">Location *</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="salary" name="salary_range" placeholder="e.g. $50k - $70k">
                                    <label for="salary">Salary Range (Optional)</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Job Description *</label>
                            <textarea class="form-control" name="description" id="editor" rows="5" required></textarea>
                        </div>

                        <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
                        <script>
                                CKEDITOR.replace('editor');
                        </script>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm">
                                🚀 Post Job Now
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
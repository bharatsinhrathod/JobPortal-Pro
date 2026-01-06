<?php
// index.php
session_start();

// 1. REDIRECT LOGGED-IN USERS (They don't need the landing page)
if(isset($_SESSION['user_id'])) {
    if($_SESSION['role'] == 'recruiter') {
        header("Location: dashboard.php");
        exit;
    }
    if($_SESSION['role'] == 'applicant') {
        header("Location: seeker_dashboard.php");
        exit;
    }
    if($_SESSION['role'] == 'admin') {
        header("Location: admin_dashboard.php");
        exit;
    }
}

require 'includes/db.php';
require 'includes/header.php';

// 2. FETCH LIVE STATS (To make the numbers real)
$jobs_count = $pdo->query("SELECT COUNT(*) FROM jobs")->fetchColumn();
$companies_count = $pdo->query("SELECT COUNT(DISTINCT company_name) FROM jobs")->fetchColumn(); // Count unique companies
$candidates_count = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'applicant'")->fetchColumn();
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<style>
    /* Custom Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
        color: white;
        padding: 100px 0;
        margin-top: -20px; /* Pull up to touch navbar */
    }
    .search-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }
    .step-icon {
        font-size: 3rem;
        color: #0d6efd;
        margin-bottom: 15px;
    }
    .category-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        border-color: #0d6efd !important;
    }
</style>

<div class="hero-section text-center">
    <div class="container">
        <h1 class="display-3 fw-bold mb-3">Find Your <span class="text-warning">Dream Job</span> Today</h1>
        <p class="lead mb-5 opacity-75">Connecting the best talent with the world's best companies.</p>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form action="jobs.php" method="GET" class="search-card p-3 rounded-pill shadow-lg">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-0 ps-3"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-0 bg-transparent fs-5" placeholder="Job title, keywords, or company...">
                        <button class="btn btn-primary rounded-pill px-5 fw-bold fs-5" type="submit">Search</button>
                    </div>
                </form>
                <div class="mt-3 text-white-50 small">
                    Popular: <span class="text-white fw-bold">Software, Marketing, Design, Finance</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white shadow-sm py-5 position-relative" style="z-index: 2;">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-3 mb-md-0">
                <h2 class="fw-bold display-5 text-primary mb-0"><?php echo number_format($jobs_count); ?>+</h2>
                <p class="text-muted fw-bold text-uppercase">Active Jobs</p>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <h2 class="fw-bold display-5 text-success mb-0"><?php echo number_format($companies_count); ?>+</h2>
                <p class="text-muted fw-bold text-uppercase">Companies Hiring</p>
            </div>
            <div class="col-md-4">
                <h2 class="fw-bold display-5 text-warning mb-0"><?php echo number_format($candidates_count); ?>+</h2>
                <p class="text-muted fw-bold text-uppercase">Candidates</p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5 mt-4">
    <div class="text-center mb-5">
        <h6 class="text-primary fw-bold text-uppercase">Process</h6>
        <h2 class="fw-bold">How It Works</h2>
        <p class="text-muted">Get hired in three simple steps</p>
    </div>

    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="card border-0 h-100 p-4">
                <div class="card-body">
                    <i class="bi bi-person-plus-fill step-icon"></i>
                    <h4 class="fw-bold">1. Create Account</h4>
                    <p class="text-muted">Register as a job seeker to create your profile and upload your resume.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 h-100 p-4">
                <div class="card-body">
                    <i class="bi bi-search step-icon"></i>
                    <h4 class="fw-bold">2. Search Jobs</h4>
                    <p class="text-muted">Browse through thousands of job openings and filter by location or role.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 h-100 p-4">
                <div class="card-body">
                    <i class="bi bi-check-circle-fill step-icon"></i>
                    <h4 class="fw-bold">3. Apply & Get Hired</h4>
                    <p class="text-muted">Submit your application in one click. Connect with HR directly when accepted.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-light py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="fw-bold">Popular Categories</h2>
                <p class="text-muted mb-0">Explore jobs by industry</p>
            </div>
            <a href="jobs.php" class="btn btn-outline-primary rounded-pill">View All Jobs</a>
        </div>

        <div class="row g-3">
            <?php 
            $categories = ['Development', 'Marketing', 'Design', 'Finance', 'Customer Service', 'Health Care'];
            foreach($categories as $cat): 
            ?>
            <div class="col-md-4 col-6">
                <a href="jobs.php?search=<?php echo urlencode($cat); ?>" class="text-decoration-none text-dark">
                    <div class="card category-card border-0 shadow-sm text-center py-4">
                        <h5 class="fw-bold mb-0"><?php echo $cat; ?></h5>
                        <small class="text-primary">Open Positions &rarr;</small>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="container py-5 my-5">
    <div class="bg-dark text-white rounded-5 p-5 position-relative overflow-hidden">
        <div class="position-relative z-2 row align-items-center">
            <div class="col-md-8">
                <h2 class="display-5 fw-bold mb-3">Are you a Recruiter?</h2>
                <p class="lead text-white-50 mb-4">Post jobs, manage candidates, and find the perfect talent for your company today.</p>
                <a href="register.php" class="btn btn-light btn-lg rounded-pill fw-bold text-primary px-5">Post a Job for Free</a>
            </div>
            <div class="col-md-4 text-center d-none d-md-block">
                <i class="bi bi-briefcase-fill" style="font-size: 8rem; opacity: 0.2;"></i>
            </div>
        </div>
    </div>
</div>

<?php 
// Fallback for footer
if(file_exists('includes/footer.php')){
    require 'includes/footer.php'; 
} else {
    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script></body></html>';
}
?>
<?php
session_start();
require 'includes/db.php';
require 'includes/header.php';

// 1. GET SEARCH TERM (If user typed something)
$search = $_GET['search'] ?? '';

// 2. BUILD SQL QUERY
if ($search) {
    // If searching: Find matches in Title, Company, Location, or Description
    $sql = "SELECT * FROM jobs 
            WHERE title LIKE ? 
            OR company_name LIKE ? 
            OR location LIKE ? 
            OR description LIKE ? 
            ORDER BY created_at DESC";
    $params = ["%$search%", "%$search%", "%$search%", "%$search%"];
} else {
    // If NOT searching: Show all jobs
    $sql = "SELECT * FROM jobs ORDER BY created_at DESC";
    $params = [];
}

// 3. FETCH JOBS
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$jobs = $stmt->fetchAll();
?>

<div class="container py-5">
    
    <div class="row justify-content-center mb-5">
        <div class="col-md-8 text-center">
            <h1 class="fw-bold mb-3">Find Your Dream Job</h1>
            <p class="text-muted mb-4">Browse through thousands of job openings</p>
            
            <form method="GET" action="jobs.php" class="d-flex gap-2">
                <input type="text" name="search" class="form-control form-control-lg rounded-pill shadow-sm ps-4" 
                       placeholder="Search by Job Title, Company, or Location..." 
                       value="<?php echo htmlspecialchars($search); ?>">
                
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm fw-bold">
                    Search
                </button>
            </form>

            <?php if($search): ?>
                <div class="mt-3">
                    <span class="text-muted">Showing results for "<strong><?php echo htmlspecialchars($search); ?></strong>"</span>
                    <a href="jobs.php" class="text-decoration-none ms-2 text-danger fw-bold">❌ Clear Search</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-4">
        <?php foreach($jobs as $job): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm hover-effect rounded-4">
                    <div class="card-body p-4 d-flex flex-column">
                        
                        <div class="mb-3">
                            <h5 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($job['title']); ?></h5>
                            <span class="text-primary fw-bold small">
                                🏢 <?php echo htmlspecialchars($job['company_name'] ?? 'Tech Corp'); ?>
                            </span>
                        </div>

                        <div class="mb-3">
                            <span class="badge bg-light text-dark border me-1">
                                📍 <?php echo htmlspecialchars($job['location']); ?>
                            </span>
                            <span class="badge bg-light text-success border">
                                💰 <?php echo htmlspecialchars($job['salary_range'] ?? 'Competitive'); ?>
                            </span>
                        </div>

                        <p class="text-muted small mb-4 flex-grow-1">
                            <?php 
                                $desc = htmlspecialchars($job['description']);
                                echo (strlen($desc) > 100) ? substr($desc, 0, 100) . '...' : $desc; 
                            ?>
                        </p>

                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <small class="text-muted">
                                🕒 <?php echo date('M d', strtotime($job['created_at'])); ?>
                            </small>
                            <a href="apply.php?job_id=<?php echo $job['job_id']; ?>" class="btn btn-outline-primary rounded-pill btn-sm fw-bold px-3">
                                View & Apply &rarr;
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if(count($jobs) == 0): ?>
            <div class="col-12 text-center py-5">
                <div class="alert alert-light border rounded-pill d-inline-block px-5">
                    <h5 class="mb-0 text-muted">😕 No jobs found matching "<?php echo htmlspecialchars($search); ?>"</h5>
                </div>
                <div class="mt-3">
                    <a href="jobs.php" class="btn btn-primary rounded-pill px-4">View All Jobs</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    /* Add subtle hover lift effect to cards */
    .hover-effect { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .hover-effect:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
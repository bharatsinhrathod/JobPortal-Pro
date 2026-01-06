<?php
session_start();
require 'includes/db.php';
require 'includes/header.php';

// 1. SECURITY: Ensure only Applicants access this
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'applicant') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// 2. FETCH USER DETAILS
$stmt = $pdo->prepare("SELECT qualification FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user_data = $stmt->fetch();
$user_qual = $user_data['qualification'] ?? '';

// 3. FETCH STATS
$total_applied = $pdo->query("SELECT COUNT(*) FROM applications WHERE user_id = $user_id")->fetchColumn();
$pending_count = $pdo->query("SELECT COUNT(*) FROM applications WHERE user_id = $user_id AND status = 'Pending'")->fetchColumn();
$accepted_count = $pdo->query("SELECT COUNT(*) FROM applications WHERE user_id = $user_id AND status = 'Accepted'")->fetchColumn();

// 4. SMART RECOMMENDATIONS
$recommended_jobs = [];
if (!empty($user_qual)) {
    $sql_match = "SELECT * FROM jobs WHERE title LIKE ? OR description LIKE ? ORDER BY created_at DESC LIMIT 3";
    $match_term = "%" . $user_qual . "%";
    $stmt = $pdo->prepare($sql_match);
    $stmt->execute([$match_term, $match_term]);
    $recommended_jobs = $stmt->fetchAll();
}

// 5. FETCH APPLICATION HISTORY
$sql_history = "SELECT applications.*, jobs.title, jobs.location, 
                u.company_name, u.email as recruiter_email 
                FROM applications 
                JOIN jobs ON applications.job_id = jobs.job_id 
                LEFT JOIN users u ON jobs.recruiter_id = u.user_id
                WHERE applications.user_id = ? 
                ORDER BY applications.applied_at DESC";

$stmt = $pdo->prepare($sql_history);
$stmt->execute([$user_id]);
$my_apps = $stmt->fetchAll();
?>

<div class="container py-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Seeker Dashboard</h2>
        <div>
            
            <a href="profile.php" class="btn btn-outline-primary rounded-pill fw-bold shadow-sm">
                ✏️ Edit Profile
            </a>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white h-100">
                <div class="card-body p-4">
                    <h3 class="fw-bold display-4"><?php echo $total_applied; ?></h3>
                    <p class="mb-0">Total Applications</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white h-100">
                <div class="card-body p-4">
                    <h3 class="fw-bold display-4 text-warning"><?php echo $pending_count; ?></h3>
                    <p class="text-muted mb-0">In Review (Pending)</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white h-100">
                <div class="card-body p-4">
                    <h3 class="fw-bold display-4 text-success"><?php echo $accepted_count; ?></h3>
                    <p class="text-muted mb-0">Shortlisted / Accepted</p>
                </div>
            </div>
        </div>
    </div>

    <?php if(!empty($user_qual) && count($recommended_jobs) > 0): ?>
        <div class="mb-5">
            <h4 class="fw-bold mb-3">Recommended for <span class="text-primary"><?php echo htmlspecialchars($user_qual); ?></span></h4>
            <div class="row">
                <?php foreach($recommended_jobs as $job): ?>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="fw-bold"><?php echo htmlspecialchars($job['title']); ?></h5>
                                <small class="text-muted">📍 <?php echo htmlspecialchars($job['location']); ?></small>
                                <hr>
                                <a href="apply.php?job_id=<?php echo $job['job_id']; ?>" class="btn btn-sm btn-primary w-100 rounded-pill">Apply Now</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">Application History</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Job Role</th>
                            <th>Company</th>
                            <th>Applied Date</th>
                            <th>Current Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($my_apps as $app): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-dark"><?php echo htmlspecialchars($app['title']); ?></td>
                                <td class="text-muted"><?php echo htmlspecialchars($app['company_name'] ?? 'Tech Corp'); ?></td>
                                <td><?php echo date('M d, Y', strtotime($app['applied_at'])); ?></td>
                                <td>
                                    <?php 
                                        $status = $app['status']; 
                                        if($status == 'Accepted') $badge = 'bg-success';
                                        elseif($status == 'Rejected') $badge = 'bg-danger';
                                        else $badge = 'bg-warning text-dark';
                                    ?>
                                    <span class="badge <?php echo $badge; ?> rounded-pill px-3 py-2">
                                        <?php echo htmlspecialchars($status); ?>
                                    </span>
                                </td>
                                
                                <td>
                                    <?php if($status == 'Accepted'): ?>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-success rounded-pill fw-bold" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#contactModal"
                                                onclick="setModalData('<?php echo $app['recruiter_email']; ?>', '<?php echo htmlspecialchars($app['title']); ?>')">
                                            ✉️ Contact Info
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted small">Wait for reply</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if(count($my_apps) == 0): ?>
                            <tr><td colspan="5" class="text-center py-5 text-muted">You haven't applied to any jobs yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="contactModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold text-success">🎉 Congratulations!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center py-4">
        <p class="text-muted mb-4">You have been shortlisted for <strong id="modalJobTitle"></strong>. Please contact the HR directly.</p>
        
        <div class="bg-light p-3 rounded-3 mb-3 d-flex justify-content-between align-items-center border">
            <span id="modalEmail" class="fw-bold text-dark font-monospace">email@example.com</span>
            <button class="btn btn-sm btn-outline-secondary" onclick="copyEmail()">📋 Copy</button>
        </div>

        <div class="d-grid gap-2">
            <a id="gmailLink" href="#" target="_blank" class="btn btn-danger rounded-pill fw-bold">
                Open in Gmail
            </a>
            <a id="defaultMailLink" href="#" class="btn btn-outline-primary rounded-pill fw-bold">
                Open Default Mail App
            </a>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function setModalData(email, jobTitle) {
        // Set Text
        document.getElementById('modalEmail').innerText = email;
        document.getElementById('modalJobTitle').innerText = jobTitle;
        
        // Set Links
        document.getElementById('defaultMailLink').href = "mailto:" + email + "?subject=Regarding Application for " + encodeURIComponent(jobTitle);
        document.getElementById('gmailLink').href = "https://mail.google.com/mail/?view=cm&fs=1&to=" + email + "&su=Regarding Application for " + encodeURIComponent(jobTitle);
    }

    function copyEmail() {
        var emailText = document.getElementById('modalEmail').innerText;
        navigator.clipboard.writeText(emailText).then(function() {
            alert('Email copied to clipboard!');
        }, function(err) {
            alert('Could not copy text: ', err);
        });
    }
</script>
</body>
</html>
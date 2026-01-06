<?php
session_start();
require 'includes/db.php';
require 'includes/header.php';

// 1. Check Login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$msg = "";

// 2. Determine "Back to Dashboard" Link
$back_link = 'index.php'; // Default
if ($role == 'recruiter') {
    $back_link = 'dashboard.php';
} elseif ($role == 'applicant') {
    $back_link = 'seeker_dashboard.php';
} elseif ($role == 'admin') {
    $back_link = 'admin_dashboard.php';
}

// 3. Handle Profile Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['full_name'];
    $qualification = $_POST['qualification'] ?? null;
    $company_name  = $_POST['company_name'] ?? null;
    
    // Image Upload Logic
    $image_path = $_POST['current_image']; 
    if (!empty($_FILES['profile_image']['name'])) {
        $target_dir = "uploads/";
        // Create uploads folder if it doesn't exist
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        
        $target_file = $target_dir . time() . "_" . basename($_FILES["profile_image"]["name"]);
        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        }
    }

    $sql = "UPDATE users SET full_name=?, qualification=?, company_name=?, profile_image=? WHERE user_id=?";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$name, $qualification, $company_name, $image_path, $user_id])) {
        $msg = "Profile updated successfully!";
        $_SESSION['name'] = $name; 
        $_SESSION['profile_image'] = $image_path; 
    }
}

// 4. Fetch User Data
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    
                    <h3 class="fw-bold mb-4">My Profile</h3>
                    
                    <?php if($msg): ?>
                        <div class="alert alert-success rounded-pill px-4"><?php echo $msg; ?></div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">
                        
                        <div class="row align-items-center mb-4">
                            <div class="col-md-3 text-center">
                                <img src="<?php echo htmlspecialchars($user['profile_image'] ?? 'uploads/default.png'); ?>" 
                                     class="rounded-circle border shadow-sm" 
                                     style="width: 100px; height: 100px; object-fit: cover;">
                            </div>
                            <div class="col-md-9">
                                <label class="form-label text-muted small fw-bold">Update Profile Picture</label>
                                <input type="file" name="profile_image" class="form-control">
                                <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($user['profile_image'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Full Name</label>
                            <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                        </div>

                        <?php if($role == 'recruiter'): ?>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Company Name</label>
                                <input type="text" name="company_name" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['company_name'] ?? ''); ?>" 
                                       placeholder="e.g. Google, Tech Corp" required>
                            </div>

                        <?php elseif($role == 'applicant'): ?>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Highest Qualification</label>
                                <input type="text" name="qualification" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['qualification'] ?? ''); ?>" 
                                       placeholder="e.g. B.Tech Computer Science">
                            </div>

                        <?php endif; ?>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Email (Cannot be changed)</label>
                            <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Save Changes</button>
                        <a href="<?php echo $back_link; ?>" class="btn btn-light rounded-pill px-4 ms-2 border">Back to Dashboard</a>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// Only require footer if it exists to avoid crashing
if(file_exists('includes/footer.php')){
    require 'includes/footer.php'; 
} else {
    // Fallback if footer.php is missing
    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script></body></html>';
}
?>
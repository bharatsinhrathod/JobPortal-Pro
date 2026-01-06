<?php
// register.php
require 'includes/db.php';
require 'includes/header.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    // Extra fields
    $qualification = $_POST['qualification'] ?? null;
    $company_name = $_POST['company_name'] ?? null;
    
    // Handle Image Upload
    $profile_image = 'default.png'; // Default if no image uploaded
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $target_dir = "uploads/";
        // Create unique name: time + original name
        $target_file = $target_dir . time() . "_" . basename($_FILES["profile_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Allow valid image formats
        if (in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $profile_image = $target_file;
            }
        }
    }

    // Check if email exists
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        $error = "Email already registered!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Updated SQL to include new columns
        $sql = "INSERT INTO users (full_name, email, password_hash, role, profile_image, qualification, company_name) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt= $pdo->prepare($sql);
        
        // Execute with all new variables
        if ($stmt->execute([$name, $email, $hashed_password, $role, $profile_image, $qualification, $company_name])) {
            header("Location: login.php?success=registered");
            exit;
        } else {
            $error = "Registration failed.";
        }
    }
}
?>

<div class="container d-flex align-items-center justify-content-center" style="min-height: 85vh;">
    <div class="col-md-6 col-lg-5">
        
        <div class="card shadow-lg border-0 rounded-4 my-5">
            <div class="card-body p-5">
                
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-dark">Create Account</h2>
                    <p class="text-muted">Join us to find your dream job</p>
                </div>

                <?php if($error): ?>
                    <div class="alert alert-danger py-2 text-center rounded-3"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" action="" enctype="multipart/form-data">
                    
                    <div class="mb-4 text-center">
                        <label for="file-upload" class="d-block mb-2 text-muted small fw-bold">Upload Profile Picture</label>
                        <input type="file" class="form-control" name="profile_image" id="file-upload" accept="image/*">
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="floatingName" name="full_name" placeholder="John Doe" required>
                        <label for="floatingName">Full Name</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="floatingEmail" name="email" placeholder="name@example.com" required>
                        <label for="floatingEmail">Email address</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="floatingPass" name="password" placeholder="Password" required>
                        <label for="floatingPass">Password</label>
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select" id="roleSelector" name="role" onchange="toggleFields()">
                            <option value="applicant" selected>Job Seeker (I want a job)</option>
                            <option value="recruiter">Recruiter (I am hiring)</option>
                        </select>
                        <label for="roleSelector">I am a...</label>
                    </div>

                    <div class="form-floating mb-4" id="qualificationDiv">
                        <input type="text" class="form-control" id="floatingQual" name="qualification" placeholder="Degree">
                        <label for="floatingQual">Highest Qualification (e.g. MCA, B.Tech)</label>
                    </div>

                    <div class="form-floating mb-4 d-none" id="companyDiv">
                        <input type="text" class="form-control" id="floatingCompany" name="company_name" placeholder="Company">
                        <label for="floatingCompany">Company Name</label>
                    </div>

                    <div class="d-grid gap-2 mb-4">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm">Sign Up</button>
                    </div>
                </form>

                <div class="text-center">
                    <p class="mb-2 text-muted small">Already have an account?</p>
                    <a href="login.php" class="btn btn-outline-primary w-100 rounded-pill fw-bold py-2 mb-3">
                        Login Instead
                    </a>
                    <a href="index.php" class="btn btn-light w-100 rounded-pill text-secondary fw-bold py-2 shadow-sm border">
                        <span class="me-2">&larr;</span> Back to Home
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
    function toggleFields() {
        var role = document.getElementById("roleSelector").value;
        var qualDiv = document.getElementById("qualificationDiv");
        var compDiv = document.getElementById("companyDiv");

        if (role === "applicant") {
            // Show Qualification, Hide Company
            qualDiv.classList.remove("d-none");
            compDiv.classList.add("d-none");
            // Optional: require qualification
            document.getElementById("floatingQual").required = true;
            document.getElementById("floatingCompany").required = false;
        } else {
            // Show Company, Hide Qualification
            qualDiv.classList.add("d-none");
            compDiv.classList.remove("d-none");
            // Optional: require company
            document.getElementById("floatingQual").required = false;
            document.getElementById("floatingCompany").required = true;
        }
    }
    
    // Run once on load to ensure correct state
    window.onload = toggleFields;
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
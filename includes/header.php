<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobPortal Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .navbar { background-color: rgba(33, 37, 41, 0.95) !important; backdrop-filter: blur(10px); padding: 15px 0; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .nav-link-custom { color: rgba(255, 255, 255, 0.75) !important; font-weight: 500; margin: 0 10px; transition: all 0.3s ease; }
        .nav-link-custom:hover { color: #ffffff !important; transform: translateY(-2px); }
        .btn-custom { background-color: #0d6efd; color: white !important; padding: 8px 25px !important; border-radius: 50px; font-weight: 600; margin-left: 10px; border: 2px solid #0d6efd; }
        .btn-custom:hover { background-color: #0b5ed7; transform: translateY(-3px); box-shadow: 0 8px 20px rgba(13, 110, 253, 0.5); }
        .profile-link:hover { opacity: 0.8; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">JobPortal <span class="text-primary">Pro</span></a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <?php if(isset($_SESSION['user_id'])): ?>
            
            <?php if($_SESSION['role'] == 'admin'): ?>
                <li class="nav-item"><a class="nav-link nav-link-custom" href="admin_dashboard.php">Overview</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom" href="manage_users.php">Manage Users</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom" href="manage_jobs.php">Manage Jobs</a></li>

            <?php elseif($_SESSION['role'] == 'recruiter'): ?>
                <li class="nav-item"><a class="nav-link btn btn-primary text-white px-4 rounded-pill shadow-sm fw-bold border-0" href="dashboard.php">Recruiter Dashboard</a></li>
            
            <?php elseif($_SESSION['role'] == 'applicant'): ?>
                <li class="nav-item"><a class="nav-link nav-link-custom" href="jobs.php">Find Jobs</a></li>
                <li class="nav-item ms-2"><a class="nav-link btn btn-primary text-white px-4 rounded-pill shadow-sm fw-bold border-0" href="seeker_dashboard.php">Seeker Dashboard</a></li>
            <?php endif; ?>

            <li class="nav-item ms-4 d-flex align-items-center">
                <a href="profile.php" class="d-flex align-items-center text-decoration-none profile-link">
                    <?php 
                        $img = $_SESSION['profile_image'] ?? 'default.png';
                        if ($img == 'default.png' || empty($img) || !file_exists($img)) { $img_src = 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png'; } else { $img_src = $img; }
                    ?>
                    <img src="<?php echo htmlspecialchars($img_src); ?>" class="rounded-circle border border-2 border-primary shadow-sm" style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px; background: white;">
                    <span class="text-white fw-bold me-3"><?php echo htmlspecialchars(explode(' ', $_SESSION['name'])[0]); ?></span>
                </a>
            </li>
            <li class="nav-item"><a class="btn btn-outline-danger rounded-pill btn-sm fw-bold px-3" href="logout.php">Logout</a></li>
        
        <?php else: ?>
            <li class="nav-item"><a class="nav-link nav-link-custom" href="login.php">Login</a></li>
            <li class="nav-item"><a class="nav-link btn btn-custom" href="register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container-fluid p-0">
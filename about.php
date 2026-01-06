<?php
session_start();
require 'includes/db.php';
require 'includes/header.php';
?>

<div class="bg-primary text-white py-5 text-center">
    <div class="container">
        <h1 class="fw-bold display-4">About JobPortal Pro</h1>
        <p class="lead opacity-75">Connecting talent with opportunity since 2026.</p>
    </div>
</div>

<div class="container py-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                 class="img-fluid rounded-4 shadow-lg" alt="Team working">
        </div>
        <div class="col-md-6 mt-4 mt-md-0 ps-md-5">
            <h6 class="text-primary fw-bold text-uppercase">Our Mission</h6>
            <h2 class="fw-bold mb-3">Empowering Careers, Building Futures</h2>
            <p class="text-muted">
                At JobPortal Pro, we believe that everyone deserves a job they love. We built this platform to bridge the gap between skilled professionals and forward-thinking companies.
            </p>
            <div class="row mt-4">
                <div class="col-6">
                    <h3 class="fw-bold text-primary">10k+</h3>
                    <small class="fw-bold text-uppercase text-muted">Success Stories</small>
                </div>
                <div class="col-6">
                    <h3 class="fw-bold text-primary">98%</h3>
                    <small class="fw-bold text-uppercase text-muted">Hiring Rate</small>
                </div>
            </div>
            
            <div class="mt-5">
                <a href="index.php" class="btn btn-outline-primary rounded-pill px-4 fw-bold">
                    &larr; Back to Home
                </a>
            </div>

        </div>
    </div>
</div>

<div class="bg-light py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Why Choose Us?</h2>
        </div>
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="bg-white p-4 rounded-4 shadow-sm h-100">
                    <div class="fs-1 mb-3">🔒</div>
                    <h5 class="fw-bold">Secure & Private</h5>
                    <p class="text-muted small">Your data is safe with us.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white p-4 rounded-4 shadow-sm h-100">
                    <div class="fs-1 mb-3">⚡</div>
                    <h5 class="fw-bold">Fast Hiring</h5>
                    <p class="text-muted small">Advanced matching algorithms.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white p-4 rounded-4 shadow-sm h-100">
                    <div class="fs-1 mb-3">🤝</div>
                    <h5 class="fw-bold">Direct Connection</h5>
                    <p class="text-muted small">No middlemen.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(file_exists('includes/footer.php')) require 'includes/footer.php'; ?>
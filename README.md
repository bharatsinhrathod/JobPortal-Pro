# 🚀 JobPortal Pro

![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![Status](https://img.shields.io/badge/Status-Complete-success?style=for-the-badge)

> **A full-stack, role-based recruitment platform connecting talent with opportunity.**

**JobPortal Pro** is a comprehensive web application designed to streamline the hiring process. It features a dedicated Applicant Tracking System (ATS), a rich-text job posting interface, and a robust Admin panel for system management.

---

## 📸 Screenshots

| Landing Page | Job Seeker Dashboard |
|:---:|:---:|
| <img src="https://via.placeholder.com/600x300?text=Landing+Page+Screenshot" alt="Home" width="100%"> | <img src="https://via.placeholder.com/600x300?text=Seeker+Dashboard" alt="Dashboard" width="100%"> |

| Recruiter Panel | Admin Overview |
|:---:|:---:|
| <img src="https://via.placeholder.com/600x300?text=Recruiter+Job+Post" alt="Recruiter" width="100%"> | <img src="https://via.placeholder.com/600x300?text=Admin+Stats" alt="Admin" width="100%"> |

*(Note: Replace these placeholder images with your actual screenshots)*

---

## 🌟 Key Features

### 🏢 For Recruiters
* **Post Jobs with Rich Text:** Create detailed job descriptions using an integrated CKEditor.
* **Applicant Tracking:** View, Accept, or Reject candidates.
* **Company Branding:** Manage company profile and details.
* **Download Resumes:** Access candidate resumes directly.

### 👨‍💻 For Job Seekers
* **Smart Search Engine:** Filter jobs by title, keyword, or company.
* **One-Click Apply:** Upload resumes (PDF/Doc) and apply instantly.
* **Application History:** Track the status of every application.
* **Direct Connect:** Unlock "Contact HR" details upon acceptance.

### 🛡️ For Admins (Super User)
* **System Overview:** Real-time statistics on users, jobs, and placements.
* **User Management:** Ban/Delete spam accounts.
* **Content Moderation:** Remove inappropriate job listings.
* **Live Activity Feed:** Monitor who is applying to what in real-time.

---

## 🛠️ Technology Stack

* **Frontend:** HTML5, CSS3, Bootstrap 5, JavaScript (ES6)
* **Backend:** PHP (PDO)
* **Database:** MySQL
* **Server:** Apache (XAMPP)
* **Tools:** CKEditor 4, SMTP Mail Simulation

---

## ⚙️ Installation Guide

Follow these steps to set up the project locally:

### 1. Clone the Repository
Download the project to your local server directory (e.g., `htdocs`).
```bash
git clone [https://github.com/YOUR-USERNAME/JobPortal-Pro.git](https://github.com/YOUR-USERNAME/JobPortal-Pro.git)
```
### 2. Database Setup
- Open phpMyAdmin (http://localhost/phpmyadmin).
- Create a new database named job_portal.
- Import the db.sql file located in the root folder of this project.

### 3. Configure Connection
- If your MySQL port is not 3306, update includes/db.php:
```bash
$host = 'localhost:3307'; // Update port if necessary
```
### 4. Create Upload Folders
- Ensure the following folders exist and have write permissions:
- uploads/
- uploads/resumes/

### 5. Run the Project
Open your browser and visit: 
```bash
http://localhost/job_portal
```
## 📂 Project Structure

```text
job_portal/
│
├── assets/
│   └── style.css            # Main stylesheet
│
├── includes/
│   ├── db.php               # Database connection settings
│   ├── header.php           # Navbar and top HTML
│   ├── footer.php           # Copyright and bottom scripts
│   └── mailer.php           # Email simulation logic
│
├── uploads/
│   ├── resumes/             # Folder for applicant CVs
│   └── default.png          # Default user profile picture
│
├── admin_dashboard.php      # Stats & Activity Feed (Admin only)
├── manage_users.php         # User deletion tool (Admin only)
├── manage_jobs.php          # Job moderation tool (Admin only)
│
├── dashboard.php            # Recruiter Main Panel
├── post_job.php             # Job Creation Page
│
├── seeker_dashboard.php     # Applicant Main Panel
├── jobs.php                 # Search & Filter Page
├── apply.php                # Job Details & Apply Form
├── profile.php              # Edit Profile (All users)
│
├── index.php                # Landing Page (Home)
├── login.php                # User Login
├── register.php             # User Registration
├── logout.php               # Session Destroy
├── about.php                # About Us Page
├── contact.php              # Contact Admin Page
│
├── db.sql                   # Database Import File
├── email_logs.txt           # File where "Sent Emails" are saved
└── README.md                # Documentation
```

### 🤝 Contributing
- Fork the repository.
- Create a new branch (git checkout -b feature-branch).
- Commit your changes.
- Push to the branch.
- Open a Pull Request.

### ❤️ Show your support
- Give a ⭐️ if this project helped you!
---

### Pro Tip for GitHub:
To make the screenshots appear in the table:
1.  Go to your GitHub repository.
2.  Click **Issues** -> **New Issue**.
3.  Drag and drop your screenshot images into the text box.
4.  GitHub will generate a link like `![image](https://user-images.githubusercontent.com/...)`.
5.  **Copy that link** and paste it into the `README.md` code where I put the `via.placeholder.com` links.
6.  Close the issue (you don't need to save it, just need the link).

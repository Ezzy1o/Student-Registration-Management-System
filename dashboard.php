<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'Inter', sans-serif;
        background: #f4f6fc;
        min-height: 100vh;
    }

    /* Header */
    .header {
        background: #4f5bd5;
        color: white;
        padding: 25px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom-left-radius: 20px;
        border-bottom-right-radius: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .header h1 { font-size: 2em; }
    .header .welcome { font-size: 1em; opacity: 0.9; }

    /* Logout Button */
    .logout-btn {
        background: #ff5e5e;
        padding: 10px 20px;
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .logout-btn:hover {
        background: #ff3b3b;
        transform: translateY(-2px);
    }

    /* Container & Cards */
    .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-top: 40px;
    }

    .card {
        background: linear-gradient(135deg, #ffffff, #e7eafc);
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        padding: 30px;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid #ddd;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    }
    .card h3 {
        font-size: 1.5em;
        color: #333;
        margin-bottom: 15px;
    }
    .card p {
        color: #555;
        margin-bottom: 20px;
        line-height: 1.5em;
    }

    /* Button - unified blue gradient */
    .btn {
        display: inline-block;
        padding: 12px 25px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        color: white;
        background: linear-gradient(135deg, #4f5bd5, #79a7ff);
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(79,91,213,0.3);
    }
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(79,91,213,0.4);
    }

    /* Center card for Program */
    .center-card {
        display: grid;
        justify-items: center;
        margin-top: 30px;
    }
    .center-card .card { max-width: 400px; }

    @media (max-width: 768px) {
        .dashboard-grid { grid-template-columns: 1fr; }
        .center-card { margin-top: 20px; }
    }
</style>
</head>
<body>

<div class="header">
    <div>
        <h1>Student Management Dashboard</h1>
        <div class="welcome">Welcome, <b><?php echo htmlspecialchars($username); ?></b> 👋</div>
    </div>
    <a href="logout.php" class="logout-btn">Logout</a>
</div>

<div class="container">

    <div class="dashboard-grid">
        <div class="card">
            <h3>Register New Student</h3>
            <p>Add a new student with full personal and academic details.</p>
            <a href="student-registration.php" class="btn">Register Student</a>
        </div>

        <div class="card">
            <h3>View All Students</h3>
            <p>Browse through all registered students and manage their records efficiently.</p>
            <a href="view-students.php" class="btn">View Students</a>
        </div>
    </div>

    <div class="center-card">
        <div class="card">
            <h3>View All Program</h3>
            <p>Check all programs and manage program information effectively.</p>
            <a href="view-programs.php" class="btn">View Program</a>
        </div>
    </div>

</div>

</body>
</html>

<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'conn.php';

$students = getAllStudents();
$search = "";

if (isset($_GET['query'])) {
    $search = $_GET['query'];
    $students = searchStudentByQuery($search);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>All Registered Students</title>
<style>
* {margin:0; padding:0; box-sizing:border-box;}

body {
    font-family: 'Arial', sans-serif;
    background: #e3f0fd;
    min-height:100vh;
}

.header {
    background: linear-gradient(90deg, #1e3c72, #2a5298);
    color: white;
    padding: 40px 0 30px 0;
    text-align: center;
    border-bottom-left-radius: 20px;
    border-bottom-right-radius: 20px;
}

.header h1 {font-size:2.5em; margin-bottom:10px;}
.header p {font-size:1.1em; opacity:0.9;}

.container {max-width:1200px; margin:40px auto; padding:0 20px;}

.search-bar {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-bottom: 30px;
}

.search-bar input {
    flex: 1;
    min-width: 0;
    padding: 12px 15px;
    border-radius: 8px;
    border: 2px solid #ccc;
    font-size: 1em;
}

.search-bar button {
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    background: #1e3c72;
    color: white;
    cursor: pointer;
    font-size: 1em;
}

.student-list {
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap:30px;
}

.student-card {
    background:white;
    border-radius:15px;
    padding:25px;
    text-align:center;
    border:2px solid #cce0ff;
    box-shadow:0 8px 20px rgba(0,0,0,0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.student-card:hover {
    transform: translateY(-5px);
    box-shadow:0 12px 25px rgba(0,0,0,0.1);
}

.student-photo {
    width:120px;
    height:120px;
    border-radius:50%;
    object-fit:cover;
    border:2px solid #1e3c72;
    margin-bottom:15px;
}

.student-card h3 {
    font-size:1.4em;
    color:#1e3c72;
    margin-bottom:10px;
    word-wrap: break-word;
}

.student-info {
    text-align:left;
    display:inline-block;
    font-size:1em;
    color:#333;
    line-height:1.6em;
    width:100%;
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal;
}

.student-info strong {
    color:#1e3c72;
}

.no-result {
    text-align:center;
    color:#555;
    font-size:1.2em;
    grid-column:1 / -1;
}

.nav-buttons {
    text-align:center;
    margin-top:40px;
}

.btn {
    background:#1e3c72;
    color:white;
    padding:12px 25px;
    border:none;
    border-radius:8px;
    text-decoration:none;
    display:inline-block;
    margin:10px;
    cursor:pointer;
    font-weight:bold;
}

.btn-secondary {
    background:#4a6fa5;
}

@media (max-width:768px){
    .search-bar input {width:70%;}
}
</style>
</head>
<body>

<div class="header">
    <h1>All Registered Students</h1>
    <p>View and manage student records</p>
</div>

<div class="container">
    <div class="search-bar">
        <form method="GET">
            <input type="text" name="query" placeholder="Enter student name or ID....." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="student-list">
        <?php
        if(!empty($students)){
            foreach($students as $student){
                echo "<div class='student-card'>";
                if(!empty($student['photo']) && file_exists($student['photo'])){
                    echo "<img src='".htmlspecialchars($student['photo'])."' class='student-photo'>";
                } else {
                    echo "<img src='uploads/default.png' class='student-photo'>";
                }
                echo "<h3>".htmlspecialchars($student['first_name'])." ".htmlspecialchars($student['last_name'])."</h3>";
                echo "<div class='student-info'>";
                echo "<strong>Student ID:</strong> ".htmlspecialchars($student['student_id'])."<br>";
                echo "<strong>Program:</strong> ".htmlspecialchars($student['program_name'])."<br>";
                echo "<strong>Year:</strong> ".htmlspecialchars($student['year'])."<br>";
                echo "<strong>Email:</strong> ".htmlspecialchars($student['email'])."<br>";
                echo "<strong>Phone:</strong> ".htmlspecialchars($student['phone'])."<br>";
                echo "<strong>Special Requirements:</strong> ".htmlspecialchars($student['requirements'])."<br>";
                echo "<strong>Address:</strong> ".htmlspecialchars($student['address'] ?? '')."<br>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<div class='no-result'>No students found.</div>";
        }
        ?>
    </div>

    <div class="nav-buttons">
        <a href="student-registration.php" class="btn">Add New Student</a>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>

</body>
</html>
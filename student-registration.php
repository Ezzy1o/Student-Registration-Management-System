<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
require 'conn.php';

$programs = getAllPrograms();
$message = "";
$messageType = "";

if ($_POST) {
    $first_name = $_POST['firstName'];
    $last_name = $_POST['lastName'];
    $student_id = $_POST['studentId'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $date_of_birth = $_POST['dateOfBirth'];
    $program = $_POST['program'];
    $year = $_POST['year'];
    $emergency_name = $_POST['emergencyName'];
    $emergency_phone = $_POST['emergencyPhone'];
    $requirements = isset($_POST['requirements']) ? implode(", ", $_POST['requirements']) : '';
    $additional_notes = $_POST['additionalNotes'];
    $address = $_POST['address'];
    $photo = null;

    
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $safeName = uniqid('', true) . '_' . basename($_FILES['photo']['name']);
        $targetPath = $uploadDir . $safeName;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
            $photo = 'uploads/' . $safeName;
        }
    }

    $checkSql = "SELECT * FROM students WHERE student_id = ? OR email = ?";
    $stmtCheck = $conn->prepare($checkSql);
    $stmtCheck->bind_param("ss", $student_id, $email);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows > 0) {
        $message = "❌ Student ID or Email already exists.";
        $messageType = "error";
    } else {
        
        $insertSql = "INSERT INTO students (first_name, last_name, student_id, email, phone, date_of_birth, program_id, year, emergency_name, emergency_phone, requirements, additional_notes, photo, address)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("ssssssisssssss",
            $first_name, $last_name, $student_id, $email, $phone,
            $date_of_birth, $program, $year, $emergency_name, $emergency_phone,
            $requirements, $additional_notes, $photo, $address
        );

        if ($stmt->execute()) {
            $message = "✅ Student registered successfully!";
            $messageType = "success";
        } else {
            $message = "⚠️ Failed to register student.";
            $messageType = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Registration Form</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
/* Reset & base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
body {
    font-family: 'Inter', sans-serif;
    background: #e3f0fd;
    min-height: 100vh;
}

/* Header */
.header {
    background: linear-gradient(90deg, #1e3c72, #2a5298);
    color: white;
    padding: 40px 0 30px 0;
    text-align: center;
    border-bottom-left-radius: 20px;
    border-bottom-right-radius: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.header h1 {
    font-size: 2.2em;
    margin-bottom: 8px;
}
.header p {
    opacity: 0.9;
}

/* Container */
.container {
    max-width: 900px;
    margin: 40px auto;
    background: white;
    border-radius: 15px;
    padding: 30px 40px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

/* Alerts */
.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 25px;
    font-weight: 600;
    text-align: center;
}
.alert.success {
    background-color: #d4edda;
    color: #155724;
    border: 2px solid #c3e6cb;
}
.alert.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 2px solid #f5c6cb;
}

/* Form layout */
form .form-row {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}
form .form-row .form-group {
    flex: 1;
}
label {
    font-weight: 600;
    margin-bottom: 5px;
    display: block;
    color: #1e3c72;
}
input[type=text],
input[type=email],
input[type=tel],
input[type=date],
select,
textarea {
    width: 100%;
    padding: 12px;
    border: 2px solid #cce0ff;
    border-radius: 8px;
    background: #f9f9f9;
    font-size: 1em;
    transition: all 0.3s ease;
}
input:focus,
select:focus,
textarea:focus {
    border-color: #1e3c72;
    background: #fff;
    outline: none;
}

/* Checkboxes */
.checkbox-group {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}
.checkbox-group input[type=checkbox] {
    transform: scale(1.2);
}

/* Buttons */
.btn {
    display: inline-block;
    padding: 12px 25px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    color: white;
    background: #1e3c72;
    transition: all 0.3s ease;
    margin: 5px;
    cursor: pointer;
    border: none;
}
.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(30,60,114,0.3);
}
.btn-secondary {
    background: #4a6fa5;
}

/* Navigation buttons */
.nav-buttons {
    text-align: center;
    margin-top: 30px;
}

/* Responsive */
@media (max-width:768px) {
    .form-row {
        flex-direction: column;
        gap: 0;
    }
    .container {
        margin: 20px;
        padding: 20px;
    }
}
</style>
</head>
<body>


<div class="header">
    <h1>Student Registration Form</h1>
    <p>Enter student information below</p>
</div>

<div class="container">
    <?php if ($message): ?>
        <div class="alert <?= $messageType ?>"><?= $message ?></div>
    <?php endif; ?>
<form method="POST" enctype="multipart/form-data">
    <div class="form-row">
        <div class="form-group">
            <label>First Name *</label>
            <input type="text" name="firstName" placeholder="Enter first name" required>
        </div>
        <div class="form-group">
            <label>Last Name *</label>
            <input type="text" name="lastName" placeholder="Enter last name" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Student ID *</label>
            <input type="text" name="studentId" placeholder="e.g. CBS20251234" required>
        </div>
        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" placeholder="example@mail.com" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Phone</label>
            <input type="tel" name="phone" placeholder="e.g. +60123456789">
        </div>
        <div class="form-group">
            <label>Date of Birth *</label>
            <input type="date" name="dateOfBirth" placeholder="YYYY-MM-DD" required>
        </div>
    </div>

    <div class="form-group">
        <label>Address</label>
        <textarea name="address" rows="3" placeholder="Enter full address"></textarea>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Program *</label>
            <select name="program" required>
                <option value="">Select Program</option>
                <?php foreach($programs as $program): ?>
                    <option value="<?= $program['id'] ?>"><?= $program['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Year *</label>
            <select name="year" required>
                <option value="">Select Year</option>
                <option value="1">Year 1</option>
                <option value="2">Year 2</option>
                <option value="3">Year 3</option>
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Emergency Name</label>
            <input type="text" name="emergencyName" placeholder="Enter emergency contact name">
        </div>
        <div class="form-group">
            <label>Emergency Phone</label>
            <input type="tel" name="emergencyPhone" placeholder="Enter emergency contact phone">
        </div>
    </div>

    <div class="form-group">
        <label>Special Requirements</label>
        <div class="checkbox-group">
            <input type="checkbox" id="transport" name="requirements[]" value="transport">
            <label for="transport">Transportation</label>
        </div>
        <div class="checkbox-group">
            <input type="checkbox" id="accommodation" name="requirements[]" value="accommodation">
            <label for="accommodation">Accommodation</label>
        </div>
        <div class="checkbox-group">
            <input type="checkbox" id="scholarship" name="requirements[]" value="scholarship">
            <label for="scholarship">Scholarship</label>
        </div>
    </div>

    <div class="form-group">
        <label>Additional Notes</label>
        <textarea name="additionalNotes" rows="3" placeholder="Enter any additional notes"></textarea>
    </div>

    <div class="form-group">
        <label>Upload Student Photo</label>
        <input type="file" name="photo">
    </div>

    <div class="nav-buttons">
        <button type="submit" class="btn">Register Student</button>
        <button type="reset" class="btn btn-secondary">Reset</button>
        <a href="dashboard.php" class="btn btn-secondary">Back</a>
    </div>
</form>

</div>

</body>
</html>

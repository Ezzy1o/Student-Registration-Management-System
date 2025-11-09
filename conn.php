<?php
$servername = '127.0.0.1';
$dbname = 'studentsystem';
$username = 'root';
$password = '';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function login($username, $password) {
    global $conn;
    $username = $conn->real_escape_string($username);
    $sql = "SELECT * FROM admin WHERE username = '$username'";
    $result = $conn->query($sql);
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
    $user = $result->fetch_assoc();
    if ($user && isset($user['password']) && password_verify($password, $user['password'])) {
        return $user;
    } else {
        return false;
    }
}

function register($fullname, $username, $email, $password) {
    global $conn;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $fullname = $conn->real_escape_string($fullname);
    $username = $conn->real_escape_string($username);
    $email = $conn->real_escape_string($email);

    $sql = "INSERT INTO admin (fullname, username, email, password) 
            VALUES ('$fullname', '$username', '$email', '$hashed_password')";
    if (!$conn->query($sql)) {
        return "Register failed: " . $conn->error;
    }
    return true;
}

function addStudent($first_name, $last_name, $student_id, $email, $phone, $date_of_birth, $program_id, $year, $emergency_name, $emergency_phone, $requirements, $additional_notes, $photo) {
    global $conn;
    $sql = "INSERT INTO students (first_name, last_name, student_id, email, phone, date_of_birth, program_id, year, emergency_name, emergency_phone, requirements, additional_notes, photo) VALUES ('$first_name', '$last_name', '$student_id', '$email', '$phone', '$date_of_birth', '$program_id', '$year', '$emergency_name', '$emergency_phone', '$requirements', '$additional_notes', '$photo')";
    $result = $conn->query($sql);
    return $result;
}


function getAllStudents(){
    global $conn;
    $sql = "SELECT students.*, programs.name as program_name FROM students LEFT JOIN programs ON students.program_id = programs.id";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}


function searchStudentByQuery($query) {
    global $conn;
    $query = trim($query);
    $query = mysqli_real_escape_string($conn, $query);
    $like = "%$query%";

    $sql = "SELECT students.*, programs.name AS program_name
            FROM students
            LEFT JOIN programs ON students.program_id = programs.id
            WHERE student_id LIKE '$like'
            OR first_name LIKE '$like'
            OR last_name LIKE '$like'
            OR CONCAT(first_name, ' ', last_name) LIKE '$like'
            OR programs.name LIKE '$like'";

    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getAllPrograms(){
    global $conn;
    $sql = "SELECT * FROM programs";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function addProgram($name){
    global $conn;
    $sql = "INSERT INTO programs (name) VALUES ('$name')";
    $result = $conn->query($sql);
    return $result;
}

function getProgramById($id){
    global $conn;
    $sql = "SELECT * FROM programs WHERE id = '$id'";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

function editProgram($id, $name){
    global $conn;
    $sql = "UPDATE programs SET name = '$name' WHERE id = '$id'";
    $result = $conn->query($sql);
    return $result;
}

function deleteProgram($id){
    global $conn;
    $sql = "DELETE FROM programs WHERE id = '$id'";
    $result = $conn->query($sql);
    return $result;
}



?>


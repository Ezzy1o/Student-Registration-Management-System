<?php
session_start();
require 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $studentId = $_POST['studentId'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dateOfBirth = $_POST['dateOfBirth'];
    $address = $_POST['address'];
    $program = $_POST['program'];
    $year = $_POST['year'];
    $emergencyName = $_POST['emergencyName'];
    $emergencyPhone = $_POST['emergencyPhone'];
    $requirements = isset($_POST['requirements']) ? $_POST['requirements'] : '';
    $additionalNotes = $_POST['additionalNotes'];

    $fullname = $firstName . ' ' . $lastName;

    $sql = "INSERT INTO students 
            (fullname, student_id, email, phone, date_of_birth, address, program, year, emergency_name, emergency_phone, requirement, notes)
            VALUES 
            ('$fullname', '$studentId', '$email', '$phone', '$dateOfBirth', '$address', '$program', '$year', '$emergencyName', '$emergencyPhone', '$requirements', '$additionalNotes')";

    if ($conn->query($sql) === TRUE) {
        header("Location: view-students.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

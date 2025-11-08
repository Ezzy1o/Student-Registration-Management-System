<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'conn.php';

$id = $_GET['id'];
$program = getProgramById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $check = $conn->query("SELECT COUNT(*) AS total FROM students WHERE program_id = '$id'");
    $row = $check->fetch_assoc();

    if ($row['total'] > 0) {
        echo "<script>
            alert('⚠️ This program is already enrolled by students and cannot be deleted.');
            window.location.href = 'view-programs.php';
        </script>";
        exit();
    }

    deleteProgram($id);

    echo "<script>
        alert('✅ Program deleted successfully.');
        window.location.href = 'view-programs.php';
    </script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Program</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            min-height: 100vh;
        }

        .header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 40px 0 30px 0;
            text-align: center;
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .header h1 { font-size: 2.5em; margin-bottom: 10px; font-weight: 600; }
        .header p { font-size: 1.1em; opacity: 0.9; }

        .container {
            max-width: 600px;
            margin: 60px auto;
            background: #fff;
            border-radius: 25px;
            padding: 40px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            text-align: center;
        }

        .container h2 {
            color: #1e3c72;
            margin-bottom: 25px;
            font-weight: 600;
        }

        .warning {
            color: #b91c1c;
            background: #fee2e2;
            border-left: 5px solid #ef4444;
            padding: 15px;
            border-radius: 10px;
            font-size: 1em;
            margin-bottom: 25px;
        }

        .program-name {
            font-size: 1.2em;
            font-weight: 500;
            color: #1e3c72;
            margin-bottom: 20px;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 10px;
            font-size: 1em;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30,60,114,0.3);
        }

        .btn.cancel {
            background: linear-gradient(135deg, #90a4ae 0%, #607d8b 100%);
        }

        .btn.delete {
            background: linear-gradient(135deg, #c62828 0%, #8e0000 100%);
        }

        .btn.delete:hover {
            box-shadow: 0 8px 25px rgba(198,40,40,0.3);
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Delete Program</h1>
    <p>Confirm before deleting this program</p>
</div>

<div class="container">
    <h2>Are you sure you want to delete?</h2>

    <div class="warning">
        ⚠️ This action cannot be undone. The program will be permanently removed.
    </div>

    <div class="program-name">
        🗂️ <?php echo htmlspecialchars($program['name']); ?>
    </div>

    <form method="POST">
        <div class="buttons">
            <a href="view-programs.php" class="btn cancel">Cancel</a>
            <button type="submit" class="btn delete">Yes, Delete</button>
        </div>
    </form>
</div>

</body>
</html>

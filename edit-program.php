<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'conn.php';

$id = $_GET['id'];
$program = getProgramById($id);

if ($_POST) {
    $program_name = trim($_POST['program_name']);
    if (!empty($program_name)) {
        editProgram($id, $program_name);

        echo "<script>
                alert('✅ Program updated successfully.');
                window.location.href = 'view-programs.php';
              </script>";
        exit();
    } else {
        $error = "Program name cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Program</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
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
}
.container h2 {
    text-align: center;
    color: #1e3c72;
    margin-bottom: 30px;
    font-weight: 600;
}
label {
    display: block;
    color: #1e3c72;
    font-weight: 500;
    margin-bottom: 10px;
}
input[type="text"] {
    width: 100%;
    padding: 12px 15px;
    border-radius: 10px;
    border: 2px solid #90caf9;
    font-size: 1em;
    margin-bottom: 25px;
    transition: all 0.3s ease;
}
input[type="text"]:focus {
    border-color: #1e3c72;
    box-shadow: 0 0 5px rgba(30,60,114,0.3);
    outline: none;
}
.buttons {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
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
.error-msg {
    background: #fee2e2;
    color: #b91c1c;
    border-left: 5px solid #ef4444;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-size: 1em;
}
</style>
</head>
<body>

<div class="header">
    <h1>Edit Program</h1>
    <p>Modify existing program details</p>
</div>

<div class="container">
    <h2>Program Information</h2>

    <?php if (isset($error)): ?>
        <div class="error-msg"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="program_name">Program Name</label>
        <input 
            type="text" 
            id="program_name" 
            name="program_name" 
            value="<?php echo htmlspecialchars($program['name']); ?>" 
            required
        >

        <div class="buttons">
            <a href="view-programs.php" class="btn cancel">Cancel</a>
            <button type="submit" class="btn">Update</button>
        </div>
    </form>
</div>

</body>
</html>

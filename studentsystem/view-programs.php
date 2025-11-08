<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_program'])) {
    $name = trim($_POST['program_name']);
    if (!empty($name)) {
        $result = addProgram($name);
        if ($result) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
            exit();
        } else {
            $error = "Failed to add program.";
        }
    } else {
        $error = "Program name is required.";
    }
}

$programs = getAllPrograms();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programs Management</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #e3f0fd;
            min-height: 100vh;
        }

        .header {
            background: linear-gradient(90deg, #1e3c72, #2a5298);
            color: white;
            padding: 40px 0 30px 0;
            text-align: center;
            border-bottom-left-radius: 25px;
            border-bottom-right-radius: 25px;
        }

        .header h1 { font-size:2.5em; margin-bottom:10px; }
        .header p { font-size:1.1em; opacity:0.9; }

        .container {
            max-width:1100px;
            margin:50px auto;
            padding:0 20px;
        }

        .top-bar {
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:30px;
        }

        .top-bar a, .top-bar button {
            background:#1e3c72;
            color:white;
            padding:12px 25px;
            border:none;
            border-radius:8px;
            font-size:1em;
            cursor:pointer;
            text-decoration:none;
            transition: all 0.3s ease;
        }

        .top-bar a:hover, .top-bar button:hover { background:#2a5298; }

        .program-list {
            background:white;
            border-radius:20px;
            box-shadow:0 10px 25px rgba(0,0,0,0.1);
            padding:30px;
        }

        .program-card {
            display:flex;
            justify-content:space-between;
            align-items:center;
            border:2px solid #cce0ff;
            border-radius:15px;
            padding:20px 25px;
            margin-bottom:20px;
            transition:all 0.3s ease;
        }

        .program-card:hover {
            transform:translateY(-4px);
            box-shadow:0 8px 18px rgba(0,0,0,0.1);
        }

        .program-card h3 {
            color:#1e3c72;
            font-size:1.2em;
            font-weight:600;
        }

        .actions {
            display:flex;
            gap:10px;
        }

        .actions a {
            text-decoration:none;
        }

        .actions .btn {
            display:inline-block;
            border:none;
            border-radius:8px;
            padding:8px 15px;
            color:white;
            font-size:0.9em;
            cursor:pointer;
            text-align:center;
            transition: all 0.3s ease;
        }

        .edit-btn { background:#1e3c72; }
        .edit-btn:hover { background:#2a5298; }

        .delete-btn { background:#b91c1c; }
        .delete-btn:hover { background:#ef4444; }

        .success-msg, .error-msg {
            padding:15px;
            border-radius:10px;
            margin-bottom:25px;
            font-size:1em;
        }

        .success-msg {
            background:#dbeafe;
            color:#1e3c72;
            border-left:5px solid #1e3c72;
        }

        .error-msg {
            background:#fee2e2;
            color:#b91c1c;
            border-left:5px solid #ef4444;
        }

        .no-result {
            text-align:center;
            color:#555;
            font-size:1.1em;
            padding:30px 0;
        }

        /* ===== Modal Styling ===== */
        .modal {
            display:none;
            position:fixed;
            top:0; left:0;
            width:100%; height:100%;
            background:rgba(0,0,0,0.5);
            justify-content:center;
            align-items:center;
            z-index:1000;
        }

        .modal-content {
            background:white;
            padding:30px;
            border-radius:20px;
            width:400px;
            box-shadow:0 5px 15px rgba(0,0,0,0.3);
            animation:fadeIn 0.3s ease;
        }

        .modal-content h2 {
            margin-bottom:20px;
            color:#1e3c72;
            text-align:center;
        }

        .modal-content input[type="text"] {
            width:100%;
            padding:10px;
            margin-bottom:20px;
            border-radius:10px;
            border:1px solid #ccc;
            font-size:1em;
        }

        .modal-buttons {
            display:flex;
            justify-content:space-between;
        }

        .modal-buttons button {
            padding:10px 20px;
            border:none;
            border-radius:10px;
            cursor:pointer;
            font-size:1em;
        }

        .add-btn { background:#1e3c72; color:white; }
        .add-btn:hover { background:#2a5298; }

        .cancel-btn { background:#b91c1c; color:white; }
        .cancel-btn:hover { background:#ef4444; }

        @keyframes fadeIn {
            from { opacity:0; transform:scale(0.9); }
            to { opacity:1; transform:scale(1); }
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Programs Management</h1>
    <p>View and manage academic programs</p>
</div>

<div class="container">

    <div class="top-bar">
        <a href="dashboard.php">← Back to Dashboard</a>
        <button onclick="openModal()">+ Add Program</button>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="success-msg">✅ Program added successfully!</div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="error-msg">⚠️ <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="program-list">
        <?php if (empty($programs)): ?>
            <div class="no-result">No programs found. Click “Add Program” to create one.</div>
        <?php else: ?>
            <?php foreach ($programs as $program): ?>
                <div class="program-card">
                    <h3><?php echo htmlspecialchars($program['name']); ?></h3>
                    <div class="actions">
                        <a href="edit-program.php?id=<?php echo $program['id']; ?>" class="btn edit-btn">Edit</a>
                        <a href="delete-program.php?id=<?php echo $program['id']; ?>" class="btn delete-btn">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div id="addProgramModal" class="modal">
    <div class="modal-content">
        <h2>Add New Program</h2>
        <form method="POST" action="">
            <input type="text" name="program_name" placeholder="Enter program name" required>
            <div class="modal-buttons">
                <button type="submit" name="add_program" class="add-btn">Add Program</button>
                <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('addProgramModal').style.display = 'flex';
    }
    function closeModal() {
        document.getElementById('addProgramModal').style.display = 'none';
    }

    setTimeout(() => {
        const success = document.querySelector('.success-msg');
        if (success) success.style.display = 'none';
    }, 3000);
</script>

</body>
</html>

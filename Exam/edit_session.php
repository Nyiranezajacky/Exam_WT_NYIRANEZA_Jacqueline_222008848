<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$session_id = $_GET['session_id'];

// Fetch session details
$stmt = $conn->prepare("SELECT * FROM sessions WHERE id = ?");
$stmt->execute([$session_id]);
$session = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$session) {
    die("Session not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Update session in database
    $stmt = $conn->prepare("UPDATE sessions SET title = ?, description = ?, start_time = ?, end_time = ? WHERE id = ?");
    $stmt->execute([$title, $description, $start_time, $end_time, $session_id]);

    // Redirect to the page where sessions are managed
    header("Location: manage_sessions.php?event_id=" . $session['event_id']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Session</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Form styles */
        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        textarea,
        input[type="datetime-local"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Edit Session</h2>
    <form action="edit_session.php?session_id=<?= htmlspecialchars($session_id) ?>" method="POST">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($session['title']) ?>" required><br>
        <label for="description">Description:</label><br>
        <textarea id="description" name="description" required><?= htmlspecialchars($session['description']) ?></textarea><br>
        <label for="start_time">Start Time:</label><br>
        <input type="datetime-local" id="start_time" name="start_time" value="<?= date('Y-m-d\TH:i', strtotime($session['start_time'])) ?>" required><br>
        <label for="end_time">End Time:</label><br>
        <input type="datetime-local" id="end_time" name="end_time" value="<?= date('Y-m-d\TH:i', strtotime($session['end_time'])) ?>" required><br>
        <button type="submit">Update Session</button>
    </form>
</body>
</html>

<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$event_id = $_GET['event_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Insert session into database
    $stmt = $conn->prepare("INSERT INTO sessions (event_id, title, description, start_time, end_time) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$event_id, $title, $description, $start_time, $end_time]);

    // Redirect to the page where sessions are managed
    header("Location: manage_sessions.php?event_id=" . $event_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Session</title>
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
    <h2>Add Session</h2>
    <form action="add_session.php" method="POST">
        <input type="hidden" name="event_id" value="<?= htmlspecialchars($event_id) ?>">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required><br>
        <label for="description">Description:</label><br>
        <textarea id="description" name="description" required></textarea><br>
        <label for="start_time">Start Time:</label><br>
        <input type="datetime-local" id="start_time" name="start_time" required><br>
        <label for="end_time">End Time:</label><br>
        <input type="datetime-local" id="end_time" name="end_time" required><br>
        <button type="submit">Add Session</button>
    </form>
</body>
</html>

<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['event_id'])) {
    header("Location: events.php");
    exit();
}

$event_id = $_GET['event_id'];

// Fetch sessions for the specific event
$stmt = $conn->prepare("SELECT * FROM sessions WHERE event_id = :event_id");
$stmt->bindParam(':event_id', $event_id);
$stmt->execute();
$sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch event details
$stmt = $conn->prepare("SELECT * FROM events WHERE id = :event_id");
$stmt->bindParam(':event_id', $event_id);
$stmt->execute();
$event = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Sessions for <?= htmlspecialchars($event['title']) ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Additional styling if needed */
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Manage Sessions for <?= htmlspecialchars($event['title']) ?></h1>
            <nav>
                <ul>
                    <li><a href="events.php">Back to Events</a></li>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <div class="container">
            <h2>Sessions</h2>
            <table>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($sessions as $session): ?>
                    <tr>
                        <td><?= htmlspecialchars($session['title']) ?></td>
                        <td><?= htmlspecialchars($session['description']) ?></td>
                        <td><?= htmlspecialchars($session['start_time']) ?></td>
                        <td><?= htmlspecialchars($session['end_time']) ?></td>
                        <td>
                            <a href="edit_session.php?session_id=<?= $session['id'] ?>" class="btn">Edit</a>
                            <a href="delete_session.php?session_id=<?= $session['id'] ?>" class="btn" onclick="return confirm('Are you sure you want to delete this session?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <a href="create_session.php?event_id=<?= $event['id'] ?>" class="btn create-btn">Create New Session</a>
        </div>
    </main>
    <footer>
        <div class="container">
            <p>&copy; 2024 Virtual Event Hosting Platform. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>

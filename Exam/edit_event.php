<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$event_id = $_GET['event_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $stmt = $conn->prepare("UPDATE events SET title = :title, description = :description, start_time = :start_time, end_time = :end_time WHERE id = :event_id");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':start_time', $start_time);
    $stmt->bindParam(':end_time', $end_time);
    $stmt->bindParam(':event_id', $event_id);

    if ($stmt->execute()) {
        header("Location: events.php");
        exit();
    } else {
        $error = "Error updating event";
    }
} else {
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = :event_id");
    $stmt->bindParam(':event_id', $event_id);
    $stmt->execute();
    $event = $stmt->fetch();

    if (!$event) {
        header("Location: events.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="containeri">
            <h1>Edit Event</h1>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <div class="container">
            <form method="post" action="edit_event.php?event_id=<?= $event_id ?>">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($event['title']) ?>" required>
                
                <label for="description">Description:</label>
                <textarea id="description" name="description" required><?= htmlspecialchars($event['description']) ?></textarea>
                
                <label for="start_time">Start Time:</label>
                <input type="datetime-local" id="start_time" name="start_time" value="<?= htmlspecialchars($event['start_time']) ?>" required>
                
                <label for="end_time">End Time:</label>
                <input type="datetime-local" id="end_time" name="end_time" value="<?= htmlspecialchars($event['end_time']) ?>" required>
                
                <button type="submit">Update Event</button>
                
                <?php if (isset($error)): ?>
                    <p class="error"><?= $error ?></p>
                <?php endif; ?>
            </form>
        </div>
    </main>
    <footer>
        <div class="containeri">
            <p>&copy; 2024 Virtual Event Hosting Platform. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>

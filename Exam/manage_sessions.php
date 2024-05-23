<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$event_id = $_GET['event_id'];

// Fetch sessions for the event
$stmt = $conn->prepare("SELECT * FROM sessions WHERE event_id = ?");
$stmt->execute([$event_id]);
$sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Sessions</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:hover {
            background-color: #f0f0f0;
        }

        /* Link styles */
        a {
            text-decoration: none;
            color: #007bff;
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #0056b3;
            color: white;
        }

        /* Button styles */
        .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: inline-block; /* Ensure buttons are on the same line */
            margin-right: 5px; /* Space between buttons */
        }

        .btn:hover {
            background-color: #0056b3;
        }

        /* Additional styles */
        .container {
            margin: 0 auto;
            padding: 20px;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        a.create-btn {
            background-color: #28a745;
        }

        a.create-btn:hover {
            background-color: #218838;
        }

        .action-buttons {
            white-space: nowrap; /* Ensure action buttons are on the same line */
        }
    </style>
</head>
<body>
    <header>
        <div class="containeri">
            <h1>Manage Sessions</h1>
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
            <h2>Sessions for Event ID: <?= htmlspecialchars($event_id) ?></h2>
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
                        <td class="action-buttons">
                            <a href="edit_session.php?session_id=<?= $session['id'] ?>" class="btn">Edit</a>
                            <a href="delete_session.php?session_id=<?= $session['id'] ?>" class="btn" onclick="return confirm('Are you sure you want to delete this session?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <a href="add_session.php?event_id=<?= htmlspecialchars($event_id) ?>" class="btn create-btn">Add New Session</a>
        </div>
    </main>
    <footer>
        <div class="containeri">
            <p>&copy; 2024 Virtual Event Hosting Platform. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>

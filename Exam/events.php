<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch all events
$stmt = $conn->prepare("SELECT * FROM events");
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Events</title>
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

  header {
    background-color: #333;
    color: #fff;
    padding: 10px 0;
}

header .containeri{
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
}

header h1 {
    margin: 0;
}

header nav ul {
    list-style: none;
    padding: 0;
    display: flex;
    gap: 10px;
}

header nav ul li {
    display: inline;
}

header nav ul li a {
    color: #fff;
    text-decoration: none;
    padding: 5px 10px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

header nav ul li a:hover {
    background-color: #575757;
}
        footer {
    background-color: #333;
    color: #fff;
    text-align: center;
    padding: 5px 0;
    position: fixed;
    width: 100%;
    bottom: 0;
    height: 1.0cm;
}

footer p {
    margin: -1;
}
    </style>
</head>
<body>
    <header>
        <div class="containeri">
            <h1>Manage Events</h1>
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
            <h2>All Events</h2>
            <table>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Actions</th>
                </tr>
               <?php foreach ($events as $event): ?>
    <tr>
        <td><?= htmlspecialchars(substr($event['title'], 0, 20)) . '...' ?></td>
        <td><?= htmlspecialchars(substr($event['description'], 0, 50)) . '...' ?></td>
        <td><?= htmlspecialchars($event['start_time']) ?></td>
        <td><?= htmlspecialchars($event['end_time']) ?></td>
        <td class="action-buttons">
            <a href="edit_event.php?event_id=<?= $event['id'] ?>" class="btn">Edit</a>
            <a href="delete_event.php?event_id=<?= $event['id'] ?>" class="btn" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
            <a href="manage_sessions.php?event_id=<?= $event['id'] ?>" class="btn">Manage Sessions</a>
        </td>
    </tr>
<?php endforeach; ?>
            </table>
            <a href="create_event.php" class="btn create-btn">Create New Event</a>
        </div>
    </main>
    <footer>
        <div class="containeri">
            <p>&copy; 2024 Virtual Event Hosting Platform. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>

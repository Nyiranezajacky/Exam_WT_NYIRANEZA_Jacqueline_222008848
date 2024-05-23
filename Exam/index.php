<?php
include 'db.php';

// Fetch upcoming events
$stmt = $conn->prepare("SELECT * FROM events ORDER BY start_time ASC LIMIT 5");
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Virtual Event Hosting Platform</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Additional styles for index.php */

        .events-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .event {
    flex-basis: calc(29.33% - 20px); /* Adjust the width to accommodate three cards per row */
    margin-bottom: 20px;
    padding: 20px;
    background-color: #f9f9f9;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

        .event h3 {
            margin-top: 0;
        }

        .event p {
            margin-bottom: 10px;
        }

        .event .btn {
            background-color: #007bff;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .event .btn:hover {
            background-color: #0056b3;
        }

        .event .event-image {
            height: 200px;
            background-size: cover;
            background-position: center;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        header {
    background-color: #333;
    color: #fff;
    padding: 10px 0;
}

header .containeri {
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


    </style>
</head>
<body>
    <header>
        <div class="containeri">
            <h1>Virtual Event Hosting Platform</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <div class="hero" style="background-image: url('img/cover_image.jpg');">
            <div class="container" style="background-color: transparent;">
                <!-- <h2>Welcome to the Virtual Event Hosting Platform</h2>
                <p>Host and join amazing events from the comfort of your home.</p> -->
                <br><br><br><br><br><br><br><br><br><br><br><br><a href="register.php" class="btn">Get Started</a>
            </div>
        </div>
        <div class="container">
            <h2>Upcoming Events</h2>
            <div class="events-list">
                <?php foreach ($events as $event): ?>
                    <div class="event">
                        <div class="event-image" style="background-image: url('<?= htmlspecialchars($event['cover_image']) ?>');"></div>
                        <h3><?= substr(htmlspecialchars($event['title']), 0, 20) ?><?= strlen($event['title']) > 20 ? "..." : "" ?></h3>
            <p><?= substr(htmlspecialchars($event['description']), 0, 100) ?><?= strlen($event['description']) > 100 ? "..." : "" ?></p>
            <hr>
                        <p><strong>Start Time:</strong> <?= htmlspecialchars($event['start_time']) ?></p>
                        <p><strong>End Time:</strong> <?= htmlspecialchars($event['end_time']) ?></p>

                        <a href="event_detail.php?event_id=<?= $event['id'] ?>" class="btn">View Details</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
    <footer>
        <div class="containeri">
            <p>&copy; 2024 Virtual Event Hosting Platform. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>

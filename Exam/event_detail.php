<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$event_id = $_GET['event_id'];

// Fetch event information
$stmt = $conn->prepare("SELECT * FROM events WHERE id = :event_id");
$stmt->bindParam(':event_id', $event_id);
$stmt->execute();
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    die("Event not found.");
}

// Debugging: Print event details to check the 'location' value
// Remove or comment this line in production
// echo "<pre>";
// print_r($event);
// echo "</pre>";

// Determine event status
$current_time = new DateTime();
$start_time = new DateTime($event['start_time']);
$end_time = new DateTime($event['end_time']);
$is_ongoing = ($current_time >= $start_time && $current_time <= $end_time);
$has_ended = ($current_time > $end_time);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Additional styles */
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .event-details {
            max-width: 600px;
            padding: 20px;
            margin-top: 20px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        .event-details img {
            max-width: 100%;
            height: auto;
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
            margin: 0;
        }

        .success {
            color: green;
            font-weight: bold;
        }

        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .copy-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-top: 10px;
            display: none;
        }
    </style>
    <script>
        function copyLink() {
            var copyText = document.getElementById("eventLink");
            copyText.select();
            copyText.setSelectionRange(0, 99999); /* For mobile devices */
            document.execCommand("copy");
            alert("Copied the link: " + copyText.value);
        }
    </script>
</head>
<body>
    <header>
        <div class="containeri">
            <h1>Virtual Event Hosting Platform</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <div class="container">
            <h2><?= htmlspecialchars($event['title']) ?></h2>
            <div class="event-details">
                <?php if (!empty($event['cover_image'])): ?>
                    <img src="<?= htmlspecialchars($event['cover_image']) ?>" alt="Event Cover Image">
                <?php endif; ?>
                <p><?= nl2br(htmlspecialchars($event['description'])) ?></p><hr>

                <p><strong>Start Time:</strong> <?= htmlspecialchars($event['start_time']) ?> |    
                <strong>End Time:</strong> <?= htmlspecialchars($event['end_time']) ?></p>
                
                <?php if ($event['location'] === 'social_media' && !empty($event['social_platform']) && !empty($event['social_link'])): ?>
                    <p><strong>Location:</strong> <?= htmlspecialchars($event['social_platform']) ?> - <a href="<?= htmlspecialchars($event['social_link']) ?>" target="_blank">Link</a></p>
                <?php elseif (!empty($event['location'])): ?>
                    <p><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
                <?php else: ?>
                    <p><strong>Location:</strong> Social Media</p>
                <?php endif; ?>
            </div>

            <?php if (!empty($event['social_link'])): ?>
                <?php
                if (isset($_SESSION['success'])) {
                    echo "<p class='success'>" . $_SESSION['success'] . "</p>";
                    unset($_SESSION['success']);
                }
                ?>
                <button onclick="copyLink()" class="btn">Share Link</button>
                <input type="text" id="eventLink" value="<?= htmlspecialchars($event['social_link']) ?>" class="copy-input" readonly>
            <?php endif; ?>

            <?php if ($is_ongoing): ?>
                <p class="success">You are attending this event</p>
            <?php elseif ($has_ended): ?>
                <p class="error">Sorry, event ended</p>
            <?php endif; ?>

            <p><a href="dashboard.php">Back to Dashboard</a></p>
        </div>
    </main>
    <footer>
        <div class="containeri">
            <p>&copy; 2024 Virtual Event Hosting Platform. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>

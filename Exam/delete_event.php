<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Delete event
    $stmt = $conn->prepare("DELETE FROM events WHERE id = :event_id");
    $stmt->bindParam(':event_id', $event_id);
    $stmt->execute();

    header("Location: events.php");
    exit();
} else {
    echo "Invalid event ID.";
}
?>

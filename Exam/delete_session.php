<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$session_id = $_GET['session_id'];

// Fetch session details to get the event_id for redirection
$stmt = $conn->prepare("SELECT event_id FROM sessions WHERE id = ?");
$stmt->execute([$session_id]);
$session = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$session) {
    die("Session not found.");
}

// Delete session from database
$stmt = $conn->prepare("DELETE FROM sessions WHERE id = ?");
$stmt->execute([$session_id]);

// Redirect to the page where sessions are managed
header("Location: manage_sessions.php?event_id=" . $session['event_id']);
exit();
?>

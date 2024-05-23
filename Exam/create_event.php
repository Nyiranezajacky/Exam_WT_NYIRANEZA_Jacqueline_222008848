<?php
session_start();
include 'db.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $errors = [];

    if (empty($_POST['title'])) {
        $errors[] = "Title is required";
    }

    if (empty($_POST['description'])) {
        $errors[] = "Description is required";
    }

    if (empty($_POST['start_time'])) {
        $errors[] = "Start time is required";
    }

    if (empty($_POST['end_time'])) {
        $errors[] = "End time is required";
    }

    // Check if there are no validation errors
    if (empty($errors)) {
        // Handle file upload for cover image
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["cover_image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["cover_image"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $errors[] = "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["cover_image"]["size"] > 500000) {
            $errors[] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $errors[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $errors[] = "Sorry, your file was not uploaded.";
        } else {
            // If everything is ok, try to upload file
            if (move_uploaded_file($_FILES["cover_image"]["tmp_name"], $target_file)) {
                // File uploaded successfully, continue with database insertion
                $cover_image_path = $target_file;
            } else {
                $errors[] = "Sorry, there was an error uploading your file.";
            }
        }

        // If there are no errors and the file was uploaded successfully, insert event into database
        if (empty($errors)) {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $start_time = $_POST['start_time'];
            $end_time = $_POST['end_time'];
            $location = $_POST['location']; // New field for location
            $social_platform = $_POST['social_platform']; // New field for social platform
            $social_link = $_POST['social_link']; // New field for social link
            $user_id = $_SESSION['user_id'];

            // Insert event data into the database
            $stmt = $conn->prepare("INSERT INTO events (user_id, title, description, start_time, end_time, location, social_platform, social_link, cover_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $title, $description, $start_time, $end_time, $location, $social_platform, $social_link, $cover_image_path]);

            // Redirect to dashboard or any other page after event creation
            header("Location: dashboard.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Event</title>
    <link rel="stylesheet" href="create_event.css"> <!-- Link to CSS file for styling -->
</head>
<body>
    <header>
        <!-- Header content -->
    </header>
    <main>
        <div class="container">
            <h2>Create a New Event</h2>
            <form method="post" enctype="multipart/form-data"> <!-- Added enctype for file upload -->
                <?php if (!empty($errors)): ?>
                    <div class="error">
                        <?php foreach ($errors as $error): ?>
                            <p><?= $error ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required><br>
                
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea><br>
                
                <label for="start_time">Start Time:</label>
                <input type="datetime-local" id="start_time" name="start_time" required><br>
                
                <label for="end_time">End Time:</label>
                <input type="datetime-local" id="end_time" name="end_time" required><br>
                
                <label for="place">Place:</label>
                <select id="place" name="place" onchange="toggleLocationField()">
                    <option value="social_media">Social Media</option>
                    <option value="physical_location">Physical Location</option>
                </select><br>

                <label for="location">Location:</label> <!-- New field for location -->
                <input type="text" id="location" name="location" disabled><br>
                <label for="social_link">Social Media Link:</label>
                <input type="text" id="social_link" name="social_link" disabled><br>

                <label for="cover_image">Cover Image:</label> <!-- Added cover image field -->
                <input type="file" id="cover_image" name="cover_image" accept="image/*"><br>
                
                <button type="submit">Create Event</button>
            </form>
        </div>
    </main>
    <script>
        function toggleLocationField() {
            var placeSelect = document.getElementById("place");
            var locationInput = document.getElementById("location");
            var socialLinkInput = document.getElementById("social_link");

            if (placeSelect.value === "social_media") {
                locationInput.disabled = true;
                socialLinkInput.disabled = false;
            } else {
                locationInput.disabled = false;
                socialLinkInput.disabled = true;
            }
        }
    </script>
</body>
</html>

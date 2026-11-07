<?php
require_once 'config.php';

if(!isLoggedIn()) {
    header('Location: index.html');
    exit;
}

$event_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $phone = $_POST['phone'];
    
    $stmt = $conn->prepare("INSERT INTO registrations (user_id, event_id, student_id, phone) VALUES (?, ?, ?, ?)");
    try {
        $stmt->execute([$_SESSION['user_id'], $event_id, $student_id, $phone]);
        header('Location: events.php?success=Registration successful');
    } catch(PDOException $e) {
        $error = "Registration failed";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for Event</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <h2 class="logo">📚 EventHub</h2>
            <ul class="nav-links">
                <li><a href="index.html">Home</a></li>
                <li><a href="events.php">Events</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <section class="events-section">
        <div class="container">
            <form id="regForm" method="POST" onsubmit="return validateForm('regForm')">
                <h2>Register for Event</h2>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <h3 style="color: #2563eb; margin-bottom: 1rem;">
                    <?php echo htmlspecialchars($event['title']); ?>
                </h3>
                
                <p><strong>Date:</strong> <?php echo date('M d, Y', strtotime($event['date'])); ?></p>
                <p><strong>Time:</strong> <?php echo date('h:i A', strtotime($event['time'])); ?></p>
                <p><strong>Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></p>
                
                <hr style="margin: 1rem 0;">
                
                <input type="text" value="<?php echo $_SESSION['name']; ?>" disabled>
                
                <input type="text" name="student_id" placeholder="Student ID (e.g., STU12345)" required>
                
                <input type="text" name="phone" placeholder="Phone Number (10 digits)" required>
                
                <button type="submit" class="btn">Complete Registration</button>
                <a href="events.php" class="btn" style="background: #6b7280; margin-top: 10px;">Cancel</a>
            </form>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2025 Event Management System</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
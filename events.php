<?php
require_once 'config.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM events WHERE title LIKE ? ORDER BY date ASC";
$stmt = $conn->prepare($sql);
$stmt->execute(["%$search%"]);
$events = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - ITUM Event System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">
            <img src="images/logo.jpeg" alt="University crest with the letters ITUM; logo located in the site header; text reads ITUM; formal academic tone">
            <span class="logo-text">ITUM</span>
            </div>
            <h2 class="logo"> Event Management</h2>
            <ul class="nav-links">
                <li><a href="index.html">Home</a></li>
                <li><a href="events.php" class="active">Events</a></li>
                <?php if(isLoggedIn()): ?>
                    <?php if(isAdmin()): ?>
                        <li><a href="admin.php">Admin</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Logout (<?php echo $_SESSION['name']; ?>)</a></li>
                <?php else: ?>
                    <li><a href="index.html">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <section class="events-section">
        <div class="container">
            <h2 style="text-align: center; margin-bottom: 2rem;">Upcoming Events</h2>
            
            <!-- Search Bar -->
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search events..." onkeyup="searchEvents()">
            </div>

            <!-- Events Grid -->
            <div class="event-grid">
                <?php if(count($events) > 0): ?>
                    <?php foreach($events as $event): ?>
                    <div class="event-card">
                        <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                        <p>📅 <strong>Date:</strong> <?php echo date('M d, Y', strtotime($event['date'])); ?></p>
                        <p>🕐 <strong>Time:</strong> <?php echo date('h:i A', strtotime($event['time'])); ?></p>
                        <p>📍 <strong>Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></p>
                        <p>👤 <strong>Organizer:</strong> <?php echo htmlspecialchars($event['organizer']); ?></p>
                        <p>💺 <strong>Capacity:</strong> <?php echo $event['capacity']; ?></p>
                        
                        <?php if(isLoggedIn() && !isAdmin()): ?>
                            <a href="register_event.php?id=<?php echo $event['id']; ?>" class="btn btn-primary">Register Now</a>
                        <?php elseif(!isLoggedIn()): ?>
                            <a href="login.php" class="btn btn-primary">Login to Register</a>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align:center;grid-column:1/-1;">No events found.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-bottom">
                <p>&copy; 2025 ITUM Event Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
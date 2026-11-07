<?php
require_once 'config.php';

if(!isAdmin()) {
    header('Location: index.html');
    exit;
}

// Handle Add Event
if(isset($_POST['add_event'])) {
    $stmt = $conn->prepare("INSERT INTO events (title, date, time, venue, description, organizer, capacity) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['title'], $_POST['date'], $_POST['time'], $_POST['venue'], $_POST['description'], $_POST['organizer'], $_POST['capacity']]);
    $success = "Event added successfully";
}

// Handle Delete Event
if(isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header('Location: admin.php');
}

// Get all events
$events = $conn->query("SELECT * FROM events ORDER BY date DESC")->fetchAll();

// Get statistics
$total_events = $conn->query("SELECT COUNT(*) FROM events")->fetchColumn();
$total_users = $conn->query("SELECT COUNT(*) FROM users WHERE role='student'")->fetchColumn();
$total_regs = $conn->query("SELECT COUNT(*) FROM registrations")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <h2 class="logo">📚 Admin Panel</h2>
            <ul class="nav-links">
                <li><a href="events.php">Events</a></li>
                <li><a href="admin.php" class="active">Admin</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <section class="events-section">
        <div class="container">
            <h2 style="text-align: center;">Admin Dashboard</h2>
            
            <?php if(isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <!-- Statistics -->
            <div class="feature-grid" style="margin: 2rem 0;">
                <div class="feature-box">
                    <div class="icon">📅</div>
                    <h3><?php echo $total_events; ?></h3>
                    <p>Total Events</p>
                </div>
                <div class="feature-box">
                    <div class="icon">👥</div>
                    <h3><?php echo $total_users; ?></h3>
                    <p>Students</p>
                </div>
                <div class="feature-box">
                    <div class="icon">📝</div>
                    <h3><?php echo $total_regs; ?></h3>
                    <p>Registrations</p>
                </div>
            </div>

            <!-- Add Event Form -->
            <form method="POST" style="max-width: 800px;">
                <h2>Add New Event</h2>
                
                <input type="text" name="title" placeholder="Event Title" required>
                
                <input type="date" name="date" required>
                
                <input type="time" name="time" required>
                
                <input type="text" name="venue" placeholder="Venue" required>
                
                <input type="text" name="organizer" placeholder="Organizer" required>
                
                <input type="number" name="capacity" placeholder="Capacity" min="1" required>
                
                <textarea name="description" placeholder="Description" rows="3" required></textarea>
                
                <button type="submit" name="add_event" class="btn">Add Event</button>
            </form>

            <!-- Events List -->
            <div style="margin-top: 3rem;">
                <h2>All Events</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Venue</th>
                            <th>Capacity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($events as $event): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($event['title']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($event['date'])); ?></td>
                            <td><?php echo date('h:i A', strtotime($event['time'])); ?></td>
                            <td><?php echo htmlspecialchars($event['venue']); ?></td>
                            <td><?php echo $event['capacity']; ?></td>
                            <td>
                                <a href="view_participants.php?id=<?php echo $event['id']; ?>" class="btn">View</a>
                                <a href="admin.php?delete=<?php echo $event['id']; ?>" 
                                   class="btn btn-delete" 
                                   onclick="return confirmDelete()">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
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
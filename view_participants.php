<?php
require_once 'config.php';

if(!isAdmin()) {
    header('Location: index.html');
    exit;
}

$event_id = $_GET['id'];

// Get event details
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();

// Get participants
$stmt = $conn->prepare("SELECT r.*, u.name, u.email FROM registrations r 
                        JOIN users u ON r.user_id = u.id 
                        WHERE r.event_id = ? 
                        ORDER BY r.reg_date DESC");
$stmt->execute([$event_id]);
$participants = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Participants</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <h2 class="logo">📚 Admin Panel</h2>
            <ul class="nav-links">
                <li><a href="events.php">Events</a></li>
                <li><a href="admin.php">Admin</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <section class="events-section">
        <div class="container">
            <h2><?php echo htmlspecialchars($event['title']); ?></h2>
            
            <div style="background: white; padding: 1.5rem; border-radius: 10px; margin: 2rem 0;">
                <p><strong>Date:</strong> <?php echo date('M d, Y', strtotime($event['date'])); ?></p>
                <p><strong>Time:</strong> <?php echo date('h:i A', strtotime($event['time'])); ?></p>
                <p><strong>Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></p>
                <p><strong>Total Registrations:</strong> <?php echo count($participants); ?> / <?php echo $event['capacity']; ?></p>
            </div>

            <h3>Registered Participants</h3>
            
            <?php if(count($participants) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Student ID</th>
                        <th>Phone</th>
                        <th>Registration Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach($participants as $p): ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($p['name']); ?></td>
                        <td><?php echo htmlspecialchars($p['email']); ?></td>
                        <td><?php echo htmlspecialchars($p['student_id']); ?></td>
                        <td><?php echo htmlspecialchars($p['phone']); ?></td>
                        <td><?php echo date('M d, Y h:i A', strtotime($p['reg_date'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p style="text-align: center; padding: 2rem; background: white; border-radius: 10px;">
                No participants yet
            </p>
            <?php endif; ?>
            
            <div style="margin-top: 2rem;">
                <a href="admin.php" class="btn">← Back to Dashboard</a>
                <button onclick="window.print()" class="btn">🖨️ Print List</button>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2025 Event Management System</p>
        </div>
    </footer>
</body>
</html>
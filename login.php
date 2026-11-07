<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : 'login';

    if ($action === 'register') {
        // Registration flow
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        // Basic validation
        if ($name === '' || $email === '' || $password === '') {
            header('Location: register.php?error=' . urlencode('Please fill all registration fields'));
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: register.php?error=' . urlencode('Invalid email address'));
            exit;
        }

        if ($password !== $confirm) {
            header('Location: register.php?error=' . urlencode('Passwords do not match'));
            exit;
        }

        // Check duplicate email
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            header('Location: register.php?error=' . urlencode('Email is already registered'));
            exit;
        }

        // Insert user
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $role = 'user';
        $insert = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $ok = $insert->execute([$name, $email, $hash, $role]);

        if ($ok) {
            // fetch inserted user id (PDO lastInsertId)
            $userId = $conn->lastInsertId();
            $_SESSION['user_id'] = $userId;
            $_SESSION['name'] = $name;
            $_SESSION['role'] = $role;
            header('Location: events.php');
            exit;
        } else {
            header('Location: register.php?error=' . urlencode('Registration failed, try again'));
            exit;
        }
    } else {
        // Login flow
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            
            if ($user['role'] == 'admin') {
                header('Location: admin.php');
            } else {
                header('Location: events.php');
            }
            exit;
        } else {
            header('Location: login.php?error=' . urlencode('Invalid credentials'));
            exit;
        }
    }
}

// If GET request — show dedicated Login view (no forms on Home)
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sign In - ITUM Events</title>
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="auth.css"><!-- auth-specific styles (separated for easy customization) -->
</head>
<body>
	<nav class="navbar">
		<div class="container">
			<div class="logo">
				<img src="images/logo.jpeg" alt="ITUM logo" style="height:34px">
			</div>
			<ul class="nav-links">
				<li><a href="index.html">Home</a></li>
				<li><a href="events.php">Events</a></li>
				<li><a href="register.php" >Create Account</a></li>
			</ul>
		</div>
	</nav>

	<main class="auth-page">
		<div class="auth-card auth-container">
			<h2 class="auth-title">Sign In</h2>

			<?php if(isset($_GET['error'])): ?>
				<div class="auth-alert auth-alert-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
			<?php endif; ?>

			<form class="auth-form" action="login.php" method="post" novalidate>
				<input type="hidden" name="action" value="login">

				<label class="auth-label" for="email">Email</label>
				<input class="auth-input" id="email" name="email" type="email" required>

				<label class="auth-label" for="password">Password</label>
				<input class="auth-input" id="password" name="password" type="password" required>

				<div class="auth-actions">
					<button type="submit" class="auth-btn auth-btn-primary">Sign In</button>
					<a class="auth-btn auth-btn-ghost" href="index.html">Back</a>
				</div>
			</form>

			<p class="auth-foot">Don't have an account? <a href="register.php">Create Account</a></p>
		</div>
	</main>

	<footer class="footer">
		<!-- ...existing footer content... -->
	</footer>
</body>
</html>
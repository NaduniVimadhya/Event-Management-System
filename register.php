<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    // Basic validation
    if ($name === '' || $email === '' || $password === '') {
        header('Location: register.php?error=' . urlencode('Please fill all required fields'));
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: register.php?error=' . urlencode('Please provide a valid email address'));
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
        header('Location: register.php?error=' . urlencode('Email already registered'));
        exit;
    }

    // Insert user
    try {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $insert = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $ok = $insert->execute([$name, $email, $hash, 'user']);

        if ($ok) {
            header('Location: login.php?success=' . urlencode('Registration successful. Please sign in.'));
            exit;
        } else {
            header('Location: register.php?error=' . urlencode('Registration failed, please try again'));
            exit;
        }
    } catch (PDOException $e) {
        // Optionally log $e->getMessage() on server
        header('Location: register.php?error=' . urlencode('Registration error, please try again'));
        exit;
    }
}

// GET -> render form
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Create Account - ITUM Events</title>
	<link rel="stylesheet" href="style.css">
	<!-- if you have auth.css, include it; otherwise style.css will style the form -->
	<link rel="stylesheet" href="auth.css">
</head>
<body>
	<nav class="navbar">
		<div class="container">
			<div class="logo">
				<img src="images/logo.jpeg" alt="ITUM logo" style="height:34px">
				<span class="logo-text">ITUM</span>
			</div>
			<ul class="nav-links">
				<li><a href="index.html">Home</a></li>
				<li><a href="events.php">Events</a></li>
				<li><a href="login.php" class="auth-link">Sign In</a></li>
			</ul>
		</div>
	</nav>

	<main class="auth-page">
		<div class="auth-card auth-container">
			<h2 class="auth-title">Create Account</h2>

			<?php if (isset($_GET['error'])): ?>
				<div class="auth-alert auth-alert-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
			<?php elseif (isset($_GET['success'])): ?>
				<div class="auth-alert" style="background:#e6ffea;color:#064;border:1px solid #c7f0d1;"><?php echo htmlspecialchars($_GET['success']); ?></div>
			<?php endif; ?>

			<form class="auth-form" action="register.php" method="post" novalidate>
				<label class="auth-label" for="name">Full Name</label>
				<input class="auth-input" id="name" name="name" type="text" required>

				<label class="auth-label" for="email">Email Address</label>
				<input class="auth-input" id="email" name="email" type="email" required>

				<label class="auth-label" for="password">Password</label>
				<input class="auth-input" id="password" name="password" type="password" required>

				<label class="auth-label" for="confirm_password">Confirm Password</label>
				<input class="auth-input" id="confirm_password" name="confirm_password" type="password" required>

				<div class="auth-actions">
					<button type="submit" class="auth-btn auth-btn-primary">Submit</button>
					<a class="auth-btn auth-btn-ghost" href="index.html">Back</a>
				</div>
			</form>

			<p class="auth-foot">Already have an account? <a href="login.php">Sign In</a></p>
		</div>
	</main>

	<footer class="footer">
		<div class="container">
			<div class="footer-bottom">
				<p>&copy; 2025 ITUM Event Management System. All rights reserved.</p>
			</div>
		</div>
	</footer>
</body>
</html>
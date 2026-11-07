<?php
// test_system.php
// Comprehensive test file for Event Management System

require_once 'config.php';

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>System Test - Event Management</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .test-container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; border-color: #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        .warning { background: #fff3cd; color: #856404; border-color: #ffeaa7; }
        .info { background: #d1ecf1; color: #0c5460; border-color: #bee5eb; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 5px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 8px; text-align: left; }
        th { background: #f8f9fa; }
    </style>
</head>
<body>
    <div class='test-container'>
        <h1>🔧 Event Management System - Comprehensive Test</h1>
        <p>This test file checks various components of your system.</p>";

// Test 1: Database Connection
echo "<div class='test-section'>";
echo "<h2>1. Database Connection Test</h2>";
try {
    if ($conn) {
        echo "<p class='success'>✅ Database connection successful!</p>";
        echo "<pre>Database: event_system\nHost: localhost\nStatus: Connected</pre>";
    } else {
        echo "<p class='error'>❌ Database connection failed!</p>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>❌ Database connection error: " . $e->getMessage() . "</p>";
}
echo "</div>";

// Test 2: Check Required Tables
echo "<div class='test-section'>";
echo "<h2>2. Database Tables Check</h2>";
$required_tables = ['users', 'events', 'registrations'];
$existing_tables = [];

try {
    $stmt = $conn->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($required_tables as $table) {
        if (in_array($table, $tables)) {
            echo "<p class='success'>✅ Table '$table' exists</p>";
            $existing_tables[] = $table;
        } else {
            echo "<p class='error'>❌ Table '$table' is missing!</p>";
        }
    }
} catch (PDOException $e) {
    echo "<p class='error'>❌ Error checking tables: " . $e->getMessage() . "</p>";
}
echo "</div>";

// Test 3: Check Table Structures
echo "<div class='test-section'>";
echo "<h2>3. Table Structure Check</h2>";
foreach ($existing_tables as $table) {
    try {
        $stmt = $conn->query("DESCRIBE $table");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h3>Table: $table</h3>";
        echo "<table>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td>{$column['Field']}</td>";
            echo "<td>{$column['Type']}</td>";
            echo "<td>{$column['Null']}</td>";
            echo "<td>{$column['Key']}</td>";
            echo "<td>{$column['Default']}</td>";
            echo "<td>{$column['Extra']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo "<p class='error'>❌ Error describing table $table: " . $e->getMessage() . "</p>";
    }
}
echo "</div>";

// Test 4: Sample Data Check
echo "<div class='test-section'>";
echo "<h2>4. Sample Data Check</h2>";

// Check users
try {
    $stmt = $conn->query("SELECT COUNT(*) as count FROM users");
    $user_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "<p class='info'>📊 Total Users: $user_count</p>";
    
    $stmt = $conn->query("SELECT * FROM users LIMIT 5");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($users) {
        echo "<h3>Sample Users:</h3>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>{$user['id']}</td>";
            echo "<td>{$user['name']}</td>";
            echo "<td>{$user['email']}</td>";
            echo "<td>{$user['role']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>❌ Error checking users: " . $e->getMessage() . "</p>";
}

// Check events
try {
    $stmt = $conn->query("SELECT COUNT(*) as count FROM events");
    $event_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "<p class='info'>📊 Total Events: $event_count</p>";
    
    $stmt = $conn->query("SELECT * FROM events LIMIT 5");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($events) {
        echo "<h3>Sample Events:</h3>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Title</th><th>Date</th><th>Venue</th><th>Capacity</th></tr>";
        foreach ($events as $event) {
            echo "<tr>";
            echo "<td>{$event['id']}</td>";
            echo "<td>{$event['title']}</td>";
            echo "<td>{$event['date']}</td>";
            echo "<td>{$event['venue']}</td>";
            echo "<td>{$event['capacity']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>❌ Error checking events: " . $e->getMessage() . "</p>";
}

// Check registrations
try {
    $stmt = $conn->query("SELECT COUNT(*) as count FROM registrations");
    $reg_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "<p class='info'>📊 Total Registrations: $reg_count</p>";
} catch (PDOException $e) {
    echo "<p class='error'>❌ Error checking registrations: " . $e->getMessage() . "</p>";
}

echo "</div>";

// Test 5: Function Tests
echo "<div class='test-section'>";
echo "<h2>5. Function Tests</h2>";

// Test isLoggedIn function
$_SESSION = []; // Clear session for test
$logged_in = isLoggedIn();
echo "<p class='" . ($logged_in ? "error" : "success") . "'>";
echo ($logged_in ? "❌" : "✅") . " isLoggedIn() without session: " . ($logged_in ? "TRUE" : "FALSE");
echo "</p>";

// Test isAdmin function
$is_admin = isAdmin();
echo "<p class='" . ($is_admin ? "error" : "success") . "'>";
echo ($is_admin ? "❌" : "✅") . " isAdmin() without session: " . ($is_admin ? "TRUE" : "FALSE");
echo "</p>";

// Test with mock admin session
$_SESSION['user_id'] = 1;
$_SESSION['role'] = 'admin';
$logged_in = isLoggedIn();
$is_admin = isAdmin();
echo "<p class='info'>🔧 Testing with mock admin session...</p>";
echo "<p class='" . ($logged_in ? "success" : "error") . "'>";
echo ($logged_in ? "✅" : "❌") . " isLoggedIn() with session: " . ($logged_in ? "TRUE" : "FALSE");
echo "</p>";
echo "<p class='" . ($is_admin ? "success" : "error") . "'>";
echo ($is_admin ? "✅" : "❌") . " isAdmin() with admin session: " . ($is_admin ? "TRUE" : "FALSE");
echo "</p>";

// Clear session after test
$_SESSION = [];

echo "</div>";

// Test 6: File Existence Check
echo "<div class='test-section'>";
echo "<h2>6. Required Files Check</h2>";
$required_files = [
    'config.php',
    'admin.php',
    'events.php',
    'login.php',
    'register.php',
    'register_event.php',
    'view_participants.php',
    'logout.php',
    'style.css',
    'auth.css',
    'script.js',
    'index.html',
    'auth.html',
    'login.html',
    'register.html'
];

foreach ($required_files as $file) {
    if (file_exists($file)) {
        echo "<p class='success'>✅ File '$file' exists</p>";
    } else {
        echo "<p class='error'>❌ File '$file' is missing!</p>";
    }
}
echo "</div>";

// Test 7: Directory and Permissions Check
echo "<div class='test-section'>";
echo "<h2>7. Directory and Permissions Check</h2>";
$directories = ['.', 'images'];

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        $writable = is_writable($dir);
        echo "<p class='" . ($writable ? "success" : "warning") . "'>";
        echo ($writable ? "✅" : "⚠️") . " Directory '$dir' is " . ($writable ? "writable" : "NOT writable");
        echo "</p>";
    } else {
        echo "<p class='error'>❌ Directory '$dir' does not exist!</p>";
    }
}

// Check images directory specifically
if (is_dir('images')) {
    $images = scandir('images');
    $image_files = array_filter($images, function($file) {
        return $file !== '.' && $file !== '..' && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file);
    });
    
    if (count($image_files) > 0) {
        echo "<p class='success'>✅ Images directory contains " . count($image_files) . " image file(s)</p>";
    } else {
        echo "<p class='warning'>⚠️ Images directory exists but contains no image files</p>";
    }
} else {
    echo "<p class='warning'>⚠️ Images directory does not exist (logo may not display)</p>";
}
echo "</div>";

// Test 8: System Information
echo "<div class='test-section'>";
echo "<h2>8. System Information</h2>";
echo "<pre>";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'N/A') . "\n";
echo "Database Driver: " . $conn->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";
echo "Database Version: " . $conn->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n";
echo "Client Version: " . $conn->getAttribute(PDO::ATTR_CLIENT_VERSION) . "\n";
echo "Current Directory: " . getcwd() . "\n";
echo "Session Status: " . session_status() . " (" . 
     (session_status() == PHP_SESSION_ACTIVE ? "Active" : 
      (session_status() == PHP_SESSION_NONE ? "None" : "Disabled")) . ")\n";
echo "</pre>";
echo "</div>";

// Test 9: Quick Actions
echo "<div class='test-section'>";
echo "<h2>9. Quick Access Links</h2>";
echo "<p><a href='index.html' target='_blank'>🏠 Home Page</a></p>";
echo "<p><a href='events.php' target='_blank'>📅 Events Page</a></p>";
echo "<p><a href='admin.php' target='_blank'>⚙️ Admin Panel</a></p>";
echo "<p><a href='login.php' target='_blank'>🔐 Login Page</a></p>";
echo "<p><a href='register.php' target='_blank'>📝 Register Page</a></p>";
echo "<p><a href='auth.html' target='_blank'>🔑 Auth Gateway</a></p>";
echo "</div>";

// Summary
echo "<div class='test-section info'>";
echo "<h2>📋 Test Summary</h2>";
echo "<p>This test file has checked:</p>";
echo "<ul>";
echo "<li>Database connection and structure</li>";
echo "<li>Table existence and sample data</li>";
echo "<li>Core PHP functions</li>";
echo "<li>File and directory structure</li>";
echo "<li>System information</li>";
echo "</ul>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>Ensure all database tables are properly created</li>";
echo "<li>Verify the admin user exists (email: admin@event.com, password: admin123)</li>";
echo "<li>Check that sample events are loaded</li>";
echo "<li>Test user registration and login functionality</li>";
echo "<li>Verify event registration process</li>";
echo "</ol>";
echo "</div>";

echo "</div></body></html>";
?>
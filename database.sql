-- Create database
CREATE DATABASE IF NOT EXISTS event_system;
USE event_system;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'student'
);

-- Events table
CREATE TABLE events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(150) NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    venue VARCHAR(200) NOT NULL,
    description TEXT,
    organizer VARCHAR(100) NOT NULL,
    capacity INT DEFAULT 50
);

-- Registrations table
CREATE TABLE registrations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    student_id VARCHAR(50) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (event_id) REFERENCES events(id)
);

-- Insert admin user (password: admin123)
INSERT INTO users (name, email, password, role) VALUES 
('Admin', 'admin@event.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert sample events
INSERT INTO events (title, date, time, venue, description, organizer, capacity) VALUES
('Web Dev Workshop', '2025-12-01', '14:00:00', 'Lab A', 'Learn web development basics', 'CS Department', 30),
('Hackathon 2025', '2025-12-10', '09:00:00', 'Main Hall', '24-hour coding competition', 'Tech Club', 50),
('Career Fair', '2025-12-15', '10:00:00', 'Auditorium', 'Meet industry professionals', 'Career Office', 100);
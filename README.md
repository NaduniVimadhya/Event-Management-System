ITUM Event Management System

A dynamic web application designed to simplify event organization and participation at the university. Students can easily browse, register, and manage event participation, while administrators efficiently handle event creation, tracking, and analytics.

Key Features
For Students

Browse & Search Events: Filter by title, organizer, or venue.

View Details: Date, time, location, capacity, and description.
Register Quickly: One-click registration with confirmation.
Account Security: Email verification & student ID integration.
Responsive Design: Works across desktop, tablet, and mobile.

For Administrators

Event Management: Create, edit, or delete events.
Analytics Dashboard: View total events, registrations, and participants.
User Management: Access participant info and registration history.
Capacity Tracking: Prevent overbooking and export participant lists.

Tech Stack

Front-End: HTML5, CSS3 (Flexbox, Grid), JavaScript (ES6+)
Back-End: PHP 7.4+ (with PDO, Sessions, Authentication)
Database: MySQL 5.7+ (users, events, registrations tables)
Server: Apache / Nginx via XAMPP or WAMP

Installation

Install XAMPP/WAMP and start Apache & MySQL.
Create Folder: C:\xampp\htdocs\event_system
Import Database: via phpMyAdmin → create event_system DB → import database.sql.
Configure: Update config.php with database credentials.
Run Project: Visit http://localhost/event_system
Admin Login:
Email: admin@event.com
Password: admin123

Folder Structure
event_system/
│
├── index.html         # Homepage
├── login.html         # Login Page
├── register.html      # Registration Page
├── config.php         # Database connection
├── /images            # Assets (e.g., logo.jpeg)

System Requirements

PHP ≥ 7.4
MySQL ≥ 5.7
Apache ≥ 2.4
2GB+ RAM, 100MB+ storage
Chrome / Firefox / Edge (latest)

Project Objectives

Centralize university event management
Simplify event discovery and registration
Provide admin tools for analytics and tracking
Enhance communication between students and organizers
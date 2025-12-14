-- Voyagely / Travel Explorer Database Schema
-- MySQL/MariaDB compatible
-- Run this file to create and populate the travel_explorer database

-- Drop existing database if needed
DROP DATABASE IF EXISTS travel_explorer;
CREATE DATABASE travel_explorer;
USE travel_explorer;

-- Users Table (for signup/login with role-based access)
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(100) NOT NULL, -- plain text for student project
  role ENUM('admin', 'user') DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Packages Table (travel packages CRUD)
CREATE TABLE packages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  description TEXT,
  price DECIMAL(10, 2) NOT NULL,
  image VARCHAR(255),
  duration_days INT,
  destination VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bookings Table (booking CRUD)
CREATE TABLE bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  package_id INT,
  booking_date DATE NOT NULL,
  status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
  number_of_people INT DEFAULT 1,
  total_price DECIMAL(10, 2),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE
);

-- Places Table (map page locations)
CREATE TABLE places (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  category VARCHAR(50), -- e.g., 'Beach', 'Mountain', 'City'
  description TEXT,
  latitude DECIMAL(10, 8),
  longitude DECIMAL(11, 8),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Conversations Table (chatbot history)
CREATE TABLE conversations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  session_id VARCHAR(100),
  user_message TEXT NOT NULL,
  bot_response TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact Messages Table (contact form submissions)
CREATE TABLE contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  subject VARCHAR(200),
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===== SAMPLE DATA =====

-- Default admin user
INSERT INTO users (name, email, password, role) VALUES
('Admin User', 'admin@travel.com', 'admin123', 'admin'),
('John Traveler', 'john@example.com', 'pass123', 'user'),
('Jane Explorer', 'jane@example.com', 'pass456', 'user');

-- Sample packages
INSERT INTO packages (title, description, price, image, duration_days, destination) VALUES
('Sahara Desert Adventure', 'Experience the golden dunes of Morocco\'s Sahara. Camel trekking, stargazing, and Bedouin camps await.', 899.99, 'sahara.jpg', 7, 'Morocco'),
('Mediterranean Coast Escape', 'Relax on pristine beaches of Greece and Croatia. Crystal-clear waters and charming coastal villages.', 1199.99, 'mediterranean.jpg', 10, 'Greece'),
('Alpine Mountain Retreat', 'Hiking, skiing, and Alpine lodge stays in the Swiss Alps. Perfect for nature lovers and adventure seekers.', 1499.99, 'alps.jpg', 8, 'Switzerland');

-- Sample places for map
INSERT INTO places (name, category, description, latitude, longitude) VALUES
('Marrakech Medina', 'City', 'Historic walled city with souks, mosques, and bustling marketplaces', 31.6295, -8.0088),
('Santorini Beach', 'Beach', 'Iconic Greek island with white-washed buildings and stunning sunsets', 36.4172, 25.4615),
('Interlaken', 'Mountain', 'Swiss mountain town nestled between two alpine lakes', 46.6863, 8.1746),
('Barcelona Gothic Quarter', 'City', 'Medieval architecture and winding streets in central Barcelona', 41.3851, 2.1734),
('Amalfi Coast', 'Beach', 'Dramatic cliffsidecapes and colorful villages along southern Italy', 40.6333, 14.6029);

-- Sample booking
INSERT INTO bookings (user_id, package_id, booking_date, status, number_of_people, total_price) VALUES
(2, 1, '2025-01-15', 'confirmed', 2, 1799.98);

-- Sample contact messages
INSERT INTO contact_messages (name, email, subject, message) VALUES
('Sarah Test', 'sarah@test.com', 'Trip Inquiry', 'I am interested in the Sahara tour. Can you provide more details?');
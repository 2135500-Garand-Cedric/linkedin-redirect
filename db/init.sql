-- init.sql: create tables
CREATE DATABASE IF NOT EXISTS linkedin_redirect;
USE linkedin_redirect;

-- Create table to store redirects
CREATE TABLE IF NOT EXISTS redirects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    referrer_url TEXT,
    country VARCHAR(100),
    city VARCHAR(100),
    visited_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
CREATE DATABASE IF NOT EXISTS user_mgmt_pro;
USE user_mgmt_pro;

CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  role_name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role_id INT NOT NULL,
  profile_image VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (role_id) REFERENCES roles(id)
);

INSERT INTO roles(role_name) VALUES ('Admin'), ('User');

-- Default Admin Account
INSERT INTO users(name,email,password_hash,role_id)
VALUES ('Admin User','admin@demo.com',
'$2y$10$E7E3XasxTbyVqNopZl2G0eVxX8uO7AplMzzmH4GhiC4p.5E9gQJ1m',1);

-- Password for admin = Admin@123

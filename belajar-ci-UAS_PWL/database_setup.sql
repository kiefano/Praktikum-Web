CREATE DATABASE IF NOT EXISTS ci4_pwl CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE ci4_pwl;

CREATE TABLE IF NOT EXISTS user (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(50) NOT NULL,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  PRIMARY KEY (id),
  UNIQUE KEY username (username),
  UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO user (username, email, password, role, created_at, updated_at) VALUES
('admin1234', 'admin@example.com', '$2y$10$wYW3m2sM7P5Xw5OZQ4l5CeQ9gUfHQ2Qf8J8U3nI6vUu7yQq7L1xU2', 'admin', NOW(), NOW())
ON DUPLICATE KEY UPDATE email = VALUES(email), role = VALUES(role), password = VALUES(password);

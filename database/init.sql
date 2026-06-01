CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  full_name VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(120) NOT NULL,
  description TEXT NOT NULL,
  status ENUM('todo', 'progress', 'done') NOT NULL DEFAULT 'todo',
  created_by INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_tasks_created_by
    FOREIGN KEY (created_by) REFERENCES users(id)
    ON DELETE SET NULL
);

INSERT INTO users (username, password_hash, full_name)
VALUES
  (
    'admin',
    '$2y$10$c25NVkdeIMqWdbgR4883YuE/s2CT1mCmGPm5Ma1XbUqGqM26ClTGe',
    'Administrator UAS'
  )
ON DUPLICATE KEY UPDATE
  password_hash = VALUES(password_hash),
  full_name = VALUES(full_name);

INSERT INTO tasks (title, description, status, created_by)
SELECT 'Siapkan Web Statis', 'Upload Web CV dari UTS dan pastikan tampil melalui reverse proxy.', 'done', u.id
FROM users u
WHERE u.username = 'admin'
  AND NOT EXISTS (SELECT 1 FROM tasks WHERE title = 'Siapkan Web Statis');

INSERT INTO tasks (title, description, status, created_by)
SELECT 'Deploy Web Dinamis', 'Jalankan aplikasi PHP native dengan koneksi MariaDB melalui environment variable.', 'progress', u.id
FROM users u
WHERE u.username = 'admin'
  AND NOT EXISTS (SELECT 1 FROM tasks WHERE title = 'Deploy Web Dinamis');

INSERT INTO tasks (title, description, status, created_by)
SELECT 'Live Test CI/CD', 'Ubah kode lokal, push ke GitHub, lalu buktikan container production auto-update.', 'todo', u.id
FROM users u
WHERE u.username = 'admin'
  AND NOT EXISTS (SELECT 1 FROM tasks WHERE title = 'Live Test CI/CD');

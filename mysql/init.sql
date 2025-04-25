CREATE DATABASE IF NOT EXISTS test_db;
CREATE DATABASE IF NOT EXISTS backend_db;
-- CREATE DATABASE IF NOT EXISTS products_db;

-- Create the users FIRST
CREATE USER IF NOT EXISTS 'envov2test'@'%' IDENTIFIED BY 'envov2test';
CREATE USER IF NOT EXISTS 'envov2backend'@'%' IDENTIFIED BY 'envov2backend';
-- CREATE USER IF NOT EXISTS 'envov2product'@'%' IDENTIFIED BY 'envov2product';

-- Now grant privileges
GRANT ALL PRIVILEGES ON test_db.* TO 'envov2test'@'%';
GRANT ALL PRIVILEGES ON backend_db.* TO 'envov2backend'@'%';
-- GRANT ALL PRIVILEGES ON products_db.* TO 'envov2product'@'%';

FLUSH PRIVILEGES;
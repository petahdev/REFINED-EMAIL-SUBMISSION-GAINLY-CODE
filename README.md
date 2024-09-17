run sql to create tables 

create table then alter table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    mobilenumber VARCHAR(15) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_admin BOOLEAN DEFAULT FALSE );
ALTER TABLE users ADD token VARCHAR(100), ADD is_verified TINYINT DEFAULT 0;

or  


to create table at once
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    mobilenumber VARCHAR(15) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_admin BOOLEAN DEFAULT FALSE,
    token VARCHAR(100),
    is_verified TINYINT DEFAULT 0
);

CREATE TABLE funds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    balance DECIMAL(10, 2) DEFAULT '0.00',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    mime_type VARCHAR(50) NOT NULL,
    image_data LONGBLOB NOT NULL
);


CREATE TABLE link_clicks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    link_url VARCHAR(255),
    click_count INT DEFAULT 0,
    last_click_time DATETIME NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE email_verification (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

To update a user to be an admin, you need to set the is_admin column to TRUE for that specific user. You can do this using an UPDATE SQL statement. Here’s how you can do it:


UPDATE users
SET is_admin = TRUE
WHERE id = ?;
Replace the ? with the id of the user you want to make an admin. For example, if you want to update the user with id 5, the SQL statement would be:



UPDATE users
SET is_admin = TRUE
WHERE id = 5;

fbwj edrn alpz alur


made with ❤ by peter wanguya

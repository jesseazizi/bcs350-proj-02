CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
);

CREATE TABLE quiz_attempt (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    num_correct_questions INT NOT NULL,
    num_total_questions INT NOT NULL,

    FOREIGN KEY (user_id) REFERENCES users(id)
);
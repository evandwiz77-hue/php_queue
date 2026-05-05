You need to make the database with this set of table :
table -> 
CREATE TABLE queues (
    id INT(11) NOT NULL AUTO_INCREMENT,
    queue_number INT(11) DEFAULT NULL,
    status ENUM('waiting', 'processing', 'done') COLLATE utf8mb4_general_ci DEFAULT 'waiting',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (id)
);

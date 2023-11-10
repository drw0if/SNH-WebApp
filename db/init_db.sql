USE snh;

CREATE TABLE `user`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(200) NOT NULL,
    `username` VARCHAR(200) NOT NULL,
    `password` CHAR(64) NOT NULL,
    `verified` BOOLEAN DEFAULT FALSE,

    PRIMARY KEY (`id`)
);

CREATE TABLE `user_verification`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `token` CHAR(64) NOT NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
);

CREATE TABLE `user_recover`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `token` CHAR(64) NOT NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
);

CREATE TABLE `session`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `token` CHAR(64) NOT NULL,
    `created_at` DATETIME NOT NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
);

CREATE TABLE `book`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(200) NOT NULL,
    `author` VARCHAR(200) NOT NULL,
    `genre` VARCHAR(200) NOT NULL,
    `picture` VARCHAR(200) DEFAULT NULL,

    `price` DECIMAL(10,2) NOT NULL,

    PRIMARY KEY (`id`)
);

CREATE TABLE `order`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `total` DECIMAL(10,2) NOT NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
);

CREATE TABLE `order_book`(
    `order_id` INT NOT NULL,
    `book_id` INT NOT NULL,
    `quantity` INT NOT NULL,

    PRIMARY KEY (`order_id`, `book_id`),
    FOREIGN KEY (`order_id`) REFERENCES `order`(`id`),
    FOREIGN KEY (`book_id`) REFERENCES `book`(`id`)
);

INSERT INTO book VALUES 
    (DEFAULT, 'Spaghetti Hacker', 'Stefano Chiccarelli', 'History', DEFAULT, 19.60),
    (DEFAULT, 'Doctor Newtron', 'Dario Bressanini', 'Comics', DEFAULT, 12.99),
    (DEFAULT, 'Il manifesto del partito comunista', 'Karl Marx', 'Economics', DEFAULT, 5.69),
    (DEFAULT, 'The Web Application Hacker''s Handbook', 'Dafydd Stuttard', 'Computer science', DEFAULT, 45.08),
    (DEFAULT, 'Guerre di rete', 'Carola Frediani', 'History', DEFAULT, 9.50);

DROP TABLE IF EXISTS `valid_patterns`;
DROP TABLE IF EXISTS `patterns`;
DROP TABLE IF EXISTS `words`;

CREATE TABLE patterns
(
    `id` int NOT NULL AUTO_INCREMENT,
    `pattern` varchar (255),
    PRIMARY KEY(`id`)
);

CREATE TABLE words
(
    `id` int NOT NULL AUTO_INCREMENT,
    `word` varchar (255),
    `hyphenated_word` varchar (255),
    PRIMARY KEY(`id`)
);

CREATE TABLE valid_patterns
(
    `id` int NOT NULL AUTO_INCREMENT,
    `fk_word_id` int NOT NULL,
    `fk_pattern_id` int NOT NULL,
    PRIMARY KEY(`id`)
);

ALTER TABLE `valid_patterns`
    ADD CONSTRAINT `valid_patterns_ibfk_1` FOREIGN KEY (`fk_word_id`) REFERENCES `words`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `valid_patterns`
    ADD CONSTRAINT `valid_patterns_ibfk_2` FOREIGN KEY (`fk_pattern_id`) REFERENCES `patterns`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
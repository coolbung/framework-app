DROP TABLE IF EXISTS `#__news`;

CREATE TABLE `#__news` (
	`news_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(255) NOT NULL,
	`alias` VARCHAR(255) NOT NULL,
	`formatted_body` TEXT NOT NULL,
	`raw_body` TEXT NOT NULL,
	PRIMARY KEY (`news_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__news` (`news_id`, `title`, `alias`, `formatted_body`, `raw_body`) VALUES
(1, 'First Article', 'first-article', '<p>This is the first of many articles in this new application.</p>', 'This is the first of many articles in this new application.'),
(2, 'Second Article', 'second-article', '<p>This is the second article in the sample data.</p>', 'This is the second article in the sample data.'),
(3, 'Third Article', 'third-article', '<p>This is the third article in the sample data series.</p>', 'This is the third article in the sample data series.');

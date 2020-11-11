drop table `accounts`;
drop TABLE `comments`;
drop TABLE `users`;
drop TABLE `projectsCategories`;
drop TABLE `categories`;
drop TABLE `projects`;

CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
    `email` varchar(60) COLLATE utf8_unicode_ci,
    `userType` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'visitor',
    `address` varchar(60) COLLATE utf8_unicode_ci,
    `province` varchar(20) COLLATE utf8_unicode_ci,
    `city` varchar(20) COLLATE utf8_unicode_ci,
    `country` varchar(20) COLLATE utf8_unicode_ci,
    `title` varchar(30) COLLATE utf8_unicode_ci,
    `branchOfficeName` varchar(30) COLLATE utf8_unicode_ci,
    `username` varchar(30) COLLATE utf8_unicode_ci,
    `password` varchar(30) COLLATE utf8_unicode_ci,
    `isAdministrator` int(11) DEFAULT 0,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `projects` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
    `createdTimestamp` timestamp DEFAULT current_timestamp(),
    `updatedTimestamp` timestamp DEFAULT current_timestamp(),
    `description` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
    `url` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
    `imagePath` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
    `userId` int(11),
    PRIMARY KEY (`id`),
    CONSTRAINT `ownFK` FOREIGN KEY (`userId`)
        REFERENCES `users` (`id`)
        ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `categories` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `projectscategories` (
    `projectId` int(11) NOT NULL,
    `categoryId` int(11) NOT NULL,
    PRIMARY KEY (`projectId`, `categoryId`),
    CONSTRAINT `belongFK` FOREIGN KEY (`projectId`)
        REFERENCES `projects` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `containFK` FOREIGN KEY (`categoryId`)
        REFERENCES `categories` (`id`)
        ON DELETE CASCADE
);



CREATE TABLE IF NOT EXISTS `comments` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `content` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
    `createdTimestamp` timestamp DEFAULT current_timestamp(),
    `updatedTimestamp` timestamp DEFAULT current_timestamp(),
    `projectId` int(11) NOT NULL,
    `userId` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `hasFK` FOREIGN KEY (`projectId`)
        REFERENCES `projects` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `addFK` FOREIGN KEY (`userId`)
        REFERENCES `users` (`id`)
        ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `accounts` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
    `password` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
    `isAdministrator` int(1) NOT NULL,
    `userId` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `createdFK` FOREIGN KEY (`userId`)
        REFERENCES `users` (`id`)
        ON DELETE CASCADE
);

INSERT INTO `users`(`name`, `userType`, `username`, `password`) VALUES 
('admin', 'administrator', 'zyg051532931225', 'zyg051532931225'),
('Yange Zhu User', 'user', 'zyg051532931225', 'zyg051532931225'),
('Yange Zhu visitor', '', '', '');

INSERT INTO `projects` (`title`,`description`, `userId`) VALUES
    ('windows form application', 'windows form application description', 2),
    ('php application', 'php application description', 2),
    ('javscript application', 'javscript application description', 2),
    ('java application', 'java application description', 2),
    ('windows form application', 'windows form application description', 2),
    ('c sharp application', 'c sharp application description', 2),
    ('asp.net application', 'asp.net application description', 2),
    ('php and javascript application', 'php and javascript application description', 2);

-- INSERT INTO `projects` (`id`, `title`, `createdTimestamp`, `updatedTimestamp`, `description`, `url`, `imagePath`) VALUES
-- (1, 'windows form application', '2020-11-07 04:03:27', '2020-11-07 04:03:27', 'windows form application description', NULL, NULL),
-- (2, 'php application', '2020-11-07 04:03:27', '2020-11-07 04:03:27', 'php application description', NULL, NULL),
-- (3, 'javscript application', '2020-11-07 04:03:27', '2020-11-07 04:03:27', 'javscript application description', NULL, NULL),
-- (4, 'java application', '2020-11-07 04:03:27', '2020-11-07 04:03:27', 'java application description', NULL, NULL),
-- (5, 'windows form application', '2020-11-07 04:03:27', '2020-11-07 04:03:27', 'windows form application description', NULL, NULL),
-- (6, 'c sharp application', '2020-11-07 04:03:27', '2020-11-07 04:03:27', 'c sharp application description', NULL, NULL),
-- (7, 'asp.net application', '2020-11-07 04:03:27', '2020-11-07 04:03:27', 'asp.net application description', NULL, NULL),
-- (8, 'php and javascript application', '2020-11-07 04:03:27', '2020-11-07 04:03:27', 'php and javascript application description', NULL, NULL);

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'C#'),
(2, 'JAVA'),
(3, 'JAVASCRIPT'),
(4, 'PHP'),
(5, 'WINDOWS FORM'),
(6, 'ASP.NET');

INSERT INTO `projectscategories`(`projectId`, `categoryId`) VALUES 
(1,5),
(2,4),
(3,3),
(4,2),
(5,5),
(6,1),
(7,6),
(8,4),
(8,3);

-- ALTER TABLE `projectscategories` drop FOREIGN KEY `containFK`;
-- ALTER TABLE `projectscategories` 
--     ADD CONSTRAINT `containFK` FOREIGN KEY (`categoryId`)
--         REFERENCES `categories` (`id`)
--         ON DELETE CASCADE;
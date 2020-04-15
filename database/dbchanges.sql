ALTER TABLE `nick_name` CHANGE `nick_name_id` `nick_name_id` BIGINT(12) NOT NULL AUTO_INCREMENT;
ALTER TABLE `statement` CHANGE `record_id` `record_id` BIGINT(12) NOT NULL AUTO_INCREMENT;
ALTER TABLE `support` CHANGE `support_id` `support_id` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT;
UPDATE statement SET value = REPLACE(value,'topic.asp','topic')

/* clean up special charcters */

UPDATE statement SET value=replace(value,"â€™","'"); 
UPDATE statement SET value=replace(value,"â€˜","'");
UPDATE statement SET value=replace(value,"â€œ",'"');
UPDATE statement SET value=replace(value,"â€",'"');

UPDATE camp SET camp_name=replace(camp_name,"â€™","'"),title=replace(title,"â€™","'"); 
UPDATE camp SET camp_name=replace(camp_name,"â€˜","'"),title=replace(title,"â€™","'");
UPDATE camp SET camp_name=replace(camp_name,"â€œ",'"'),title=replace(title,"â€™","'");
UPDATE camp SET camp_name=replace(camp_name,"â€",'"'),title=replace(title,"â€™","'");


UPDATE topic SET topic_name=replace(topic_name,"â€™","'"); 
UPDATE topic SET topic_name=replace(topic_name,"â€˜","'");
UPDATE topic SET topic_name=replace(topic_name,"â€œ",'"');
UPDATE topic SET topic_name=replace(topic_name,"â€",'"');

UPDATE post SET body=replace(body,"â€™","'"); 
UPDATE post SET body=replace(body,"â€˜","'");
UPDATE post SET body=replace(body,"â€œ",'"');
UPDATE post SET body=replace(body,"â€",'"');

UPDATE post SET body=replace(body,"â€¦",'');

UPDATE thread SET body=replace(body,"â€™","'"),title=replace(title,"â€™","'"); 
UPDATE thread SET body=replace(body,"â€˜","'"),title=replace(title,"â€˜","'");
UPDATE thread SET body=replace(body,"â€œ",'"'),title=replace(title,"â€œ",'"');
UPDATE thread SET body=replace(body,"â€",'"'),title=replace(title,"â€",'"');

UPDATE thread SET body=replace(body,"â€¦",''),title=replace(title,"â€¦",'');


--- 11th Nov --
--
-- Table structure for table `change_agree_logs`
--

CREATE TABLE `change_agree_logs` (
  `id` int(11) NOT NULL,
  `change_id` int(11) DEFAULT NULL,
  `topic_num` int(11) NOT NULL,
  `camp_num` int(11) DEFAULT NULL,
  `nick_name_id` int(11) NOT NULL,
  `change_for` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `change_agree_logs`
--
ALTER TABLE `change_agree_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `change_agree_logs`
--
ALTER TABLE `change_agree_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- grace period ---
ALTER TABLE `statement` ADD `grace_period` INT NOT NULL DEFAULT '0' AFTER `language`;
ALTER TABLE `camp` ADD `grace_period` INT NOT NULL DEFAULT '0' AFTER `camp_about_nick_id`;
ALTER TABLE `topic` ADD `grace_period` INT NOT NULL DEFAULT '0' AFTER `namespace_id`;

--
-- Table structure for table `news_feed`
--

CREATE TABLE `news_feed` (
  `id` int(11) NOT NULL,
  `topic_num` int(11) NOT NULL,
  `camp_num` int(11) NOT NULL,
  `display_text` text NOT NULL,
  `link` varchar(555) NOT NULL,
  `order_id` int(11) NOT NULL,
  `available_for_child` int(11) NOT NULL DEFAULT '0',
  `submit_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `news_feed`
--
ALTER TABLE `news_feed`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `news_feed`
--
ALTER TABLE `news_feed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  
  
ALTER TABLE `person` ADD `phone_number` VARCHAR(10) NULL AFTER `country`, ADD `mobile_carrier` VARCHAR(50) NULL AFTER `phone_number`, ADD `mobile_verified` INT NOT NULL DEFAULT '0' AFTER `mobile_carrier`;  


-- 1-july 2019-- video podcast---

CREATE TABLE `videopodcast` (
  `id` int(11) NOT NULL,
  `html_content` longtext,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `videopodcast`
--

INSERT INTO `videopodcast` (`id`, `html_content`, `created_at`, `updated_at`) VALUES
(1, '<h3>Video Podcast</h3>\r\n<div style=\"background-color: #f0efef;padding: .75rem 1rem;font-size: 15px;margin-bottom: 2rem;float: left; width: 100%;\">\r\n<span style=\"color:#0000ff; font-size:15px\">Episode 1 of 4</span>\r\n<object height=\"275\" width=\"480\" data=\"https://www.youtube.com/embed/tgbNymZ7vqY\"\"></object>\r\n<div>', '2019-06-19 18:29:23', '2019-06-19 18:29:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `videopodcast`
--
ALTER TABLE `videopodcast`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `videopodcast`
--
ALTER TABLE `videopodcast`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;
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
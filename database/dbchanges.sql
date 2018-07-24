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

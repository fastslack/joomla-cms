ALTER TABLE #__content ADD stage_id int null after state;

--
-- Creating Associations for existing content
--
INSERT INTO `#__content` (`stage_id`)
SELECT s.`id`
FROM `#__content` AS c
         LEFT JOIN #__workflow_stages AS s ON c.state = s.`condition`;


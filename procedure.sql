
### get_rrclose

DELIMITER $$
CREATE DEFINER=`dev1`@`%` PROCEDURE `get_rrclose3`()
    NO SQL
SELECT rc.id, rc.rid, rb.name, rc.start_time, rc.end_time FROM cm_rr_close rc JOIN cm_rr_base rb ON rc.id = rb.rid ORDER BY start_time DESC LIMIT 30$$
DELIMITER ;


### insert_rrclose

DELIMITER $$
CREATE DEFINER=`dev1`@`%` PROCEDURE `insert_rrclose3`(IN `iv_rid` INT(10), IN `iv_start_time` TIMESTAMP, IN `iv_end_time` TIMESTAMP)
    NO SQL
INSERT INTO cm_rr_close(rid, start_time, end_time) 
VALUES(iv_rid, iv_start_time, iv_end_time)$$
DELIMITER ;


### update_rrclose

DELIMITER $$
CREATE DEFINER=`dev1`@`%` PROCEDURE `update_rrclose3`(IN `iv_rid` INT(10), IN `iv_start_time` TIMESTAMP, IN `iv_end_time` TIMESTAMP, IN `iv_close_id` INT(10))
    NO SQL
UPDATE cm_rr_close
SET 
rid = iv_rid, 
start_time = iv_start_time,
end_time = iv_end_time
WHERE id = iv_close_id$$
DELIMITER ;


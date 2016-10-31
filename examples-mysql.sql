
-- test input
-- birthday - 1477684926, phone 14475544564, gender - M, 

SELECT t.* FROM krtech_test.discounts t 
where 
 t.`services` regexp '[^\d]?(6|3)[^\d]?' and 
 (t.`birthday_before` is null OR (1477684926 - UNIX_TIMESTAMP(now()) <= `birthday_before`) ) and
 (t.`birthday_after` is null OR (UNIX_TIMESTAMP(now()) - 1477684926 <= `birthday_after`) ) and 
 (t.`phone` is null OR '14475544564' regexp t.`phone`) and -- или нет, или требуется наличие, или регулярка
 (t.`gender` is null OR t.gender = 'M') and
 (t.`date_start` is null or t.`date_start` <= UNIX_TIMESTAMP(now())) and
 (t.`date_end` is null or t.`date_end` > UNIX_TIMESTAMP(now())); 
 

-- dates
select now(), UNIX_TIMESTAMP(now()), UNIX_TIMESTAMP(now()) - 604800,FROM_UNIXTIME(UNIX_TIMESTAMP(now()) - 604800) ;
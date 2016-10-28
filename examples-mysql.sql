
SELECT t.* FROM krtech_test.discounts t 
where t.`services` regexp '[^\d]?(6|3)[^\d]?' 
 and (t.`birthday_before`= null OR (1477684926 - UNIX_TIMESTAMP(now()) <= `birthday_before`) )
 and (t.`birthday_after`= null OR (UNIX_TIMESTAMP(now()) - 1477684926 <= `birthday_after`) )
 and (t.`phone` = null OR )
 ;

-- dates
select now(), UNIX_TIMESTAMP(now()), UNIX_TIMESTAMP(now()) - 604800,FROM_UNIXTIME(UNIX_TIMESTAMP(now()) - 604800) ;
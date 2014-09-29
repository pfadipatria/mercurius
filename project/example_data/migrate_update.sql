
-- -----------------------------------------------------
-- Export keys
--
-- We might want use a different field for holder/dholder,
-- as its not unique
-- -----------------------------------------------------
SELECT
   key.elnumber,
   key.code,
   key.type,
   keycolor.colorid AS colorid,
   keymech.number AS mechnumber,
   keystatus.name AS statusname,
   person.name AS holdername,
   dperson.name AS dholdername,
   key.comment,
   key.communication,
   key.lastupdate
FROM `key`
LEFT JOIN keycolor ON (key.color = keycolor.id)
LEFT JOIN keymech ON (key.mech = keymech.id)
LEFT JOIN keystatus ON (key.status = keystatus.id)
LEFT JOIN person ON (key.holder = person.id)
LEFT JOIN person AS dperson ON (key.dholder = dperson.id)
INTO OUTFILE '/tmp/orders.csv'
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
;

#!/bin/bash

# cat schloesser.csv | ~/devel/skeymanager/project/sc2sql.sh

while read line
do
   #echo "bearbeite $line"
   heim="$( echo "${line}" | cut -d \; -f 1 | tr -d "\"" )"
   name="$( echo "${line}" | cut -d \; -f 2 | tr -d "\"" | cut -d \   -f 1 )"
   bez="$( echo "${line}" | cut -d \; -f 2 | tr -d "\"" | cut -d \   -f 2- )"
   sc="$( echo "${line}" | cut -d \; -f 3 | tr -d "\"" | cut -d \. -f 2)"

   # echo "Inserting heim: ${heim} name: ${name} bez: ${bez} sc: ${sc}"
   # continue
   echo "
INSERT INTO doorlock (
                     number,
                     name,
                     sc,
                     place
                    )
                    VALUES
                    (
                     '${name}',
                     '${bez}',
                     '${sc}',
                     (SELECT id FROM doorplace WHERE name = '${heim}')
                    );
" | iconv -f ISO-8859-1 -t UTF-8
   
done

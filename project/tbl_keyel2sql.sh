#!/bin/bash

# cat tbl_keyel.csv | cut -d \; -f 1,2,3,4,22,23 | ~/devel/skeymanager/project/tbl_keyel2sql.sh

while read line
do
   # echo "bearbeite $line"
   elnumber="$( echo "${line}" | cut -d \; -f 1 | tr -d "\"" )"
   code="$( echo "${line}" | cut -d \; -f 2 | tr -d "\"" )"
   type="$( echo "${line}" | cut -d \; -f 3 | tr -d "\"" )"
   mechnumber="$( echo "${line}" | cut -d \; -f 4 | tr -d "\"" )"
   com="$( echo "${line}" | cut -d \; -f 5 | tr -d "\"" )"
   color="$( echo "${line}" | cut -d \; -f 22 | tr -d "\"" )"
   name="$( echo "${line}" | cut -d \; -f 23 | tr -d "\"" )"

   # echo "Inserting elnummer: ${elnumber} code: ${code} mechnumber: ${mechnumber} color: ${color} name: ${name}"
   echo "
INSERT INTO doorkey (
                     elnumber,
                     code,
                     type,
                     mech,
                     communication,
                     color,
                     comment
                    )
                    VALUES
                    (
                     '${elnumber}',
                     '${code}',
                     '${type}',
                     (SELECT id FROM doorkeymech WHERE number = '${mechnumber}'),
                     '${com}',
                     (SELECT id FROM doorkeycolor WHERE colorid = '${color}'),
                     'imported ${name}'
                    );
"
   
done

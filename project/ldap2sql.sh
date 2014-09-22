#!/bin/bash

# cat project/ldapExport | ./project/ldap2sql.sh  | mysql 

while read line
do
   #echo "bearbeite $line"
   cn="$( echo "${line}" | cut -d \, -f 5 | tr -d "\"" )"
   uid="$( echo "${line}" | cut -d \, -f 6 | tr -d "\"" )"
   uidnumber="$( echo "${line}" | cut -d \, -f 7 | tr -d "\"" )"

   # echo "Inserting cn: ${cn} uid: ${uid} uidnumber: ${uidnumber}"
   # continue
   echo "
INSERT INTO doorperson (
                     name,
                     uid,
                     uidnumber
                    )
                    VALUES
                    (
                     '${cn}',
                     '${uid}',
                     '${uidnumber}'
                    );
" | iconv -f ISO-8859-1 -t UTF-8
   
done

<?php

namespace SKeyManager\Repository;

class PersonRepository {

    protected $locationPattern = '/person/show/%s';

    function __construct() {
        $this->query = '
            SELECT
                id,
                name,
                uid,
                uidnumber,
                mdbid,
                comment
            FROM doorperson
            ORDER BY name;
        ';
    }


    function getAll() {
        $con = openDb();
        $dbresult = queryDb($con, $this->query);
        $rows = array();
        $locations = array();
        while ($row = mysqli_fetch_assoc($dbresult)){
            $locations[] = sprintf($his->locationPattern, $row['id']);
            $rows[] = $row;
        }

        return array($rows, $locations);
    }

}

<?php

namespace SKeyManager\Repository;

class PersonRepository extends AbstractRepository {

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

}

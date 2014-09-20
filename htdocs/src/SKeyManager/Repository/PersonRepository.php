<?php

namespace SKeyManager\Repository;

class PersonRepository extends AbstractRepository {

    protected $locationPattern = '/person/%s';

    function __construct() {
        $this->select = '
            SELECT
                id,
                name,
                uid,
                uidnumber,
                mdbid,
                comment
        ';

        $this->from = '
            FROM doorperson
        ';

        $this->order = '
            ORDER BY name;
        ';
    }

    function getAll() {
        return $this->query('', 'SKeyManager\Entity\Person');
    }

}

<?php

namespace SKeyManager\Entity;

class PersonEntity extends AbstractEntity {

    protected $locationPattern = '/person/show/%s';

    function __construct($id) {
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

        $this->where = '
           WHERE id = ' . $id . '
        ';

        $this->order = '
            ORDER BY name;
        ';
    }

}

<?php

namespace SKeyManager\Entity;

class PersonEntity extends AbstractEntity {

    protected $locationPattern = '/person/show/%s';
    var $id;

    function __construct($id) {
        $this->id = $id;

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

    function getAll() {
        return $this->query($this->id);
    }

}
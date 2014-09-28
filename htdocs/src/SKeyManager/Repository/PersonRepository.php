<?php

namespace SKeyManager\Repository;

class PersonRepository extends AbstractRepository {

    protected $locationPattern = '/person/%s';

    function __construct() {
        $this->select = '
            SELECT
                id
        ';

        $this->where = '
        ';

        $this->from = '
            FROM `person`
        ';

        $this->order = '
            ORDER BY name;
        ';
    }

    function getAll() {
        return $this->query('', 'SKeyManager\Entity\Person');
    }

    function getSubSet($condition) {
        return $this->query($condition, 'SKeyManager\Entity\Person');
    }

}

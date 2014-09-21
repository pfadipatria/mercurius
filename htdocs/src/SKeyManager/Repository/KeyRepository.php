<?php

namespace SKeyManager\Repository;

class KeyRepository extends AbstractRepository {

    protected $locationPattern = '/key/%s';
    var $keys = array();

    function __construct() {
        $this->select = '
            SELECT
               id
        ';

        $this->from = '
            FROM doorkey
        ';

        $this->where = '
        ';

        $this->order = '
            ORDER BY code
        ';
    }

    function getByPersonId($id) {
        return $this->query('WHERE owner = '.$id, 'SKeyManager\Entity\Key');
    }

    function getAll() {
        return $this->query('', 'SKeyManager\Entity\Key');
    }
}

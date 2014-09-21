<?php

namespace SKeyManager\Repository;

class LockRepository extends AbstractRepository {

    protected $locationPattern = '/lock/%s';

    function __construct() {
        $this->select = '
            SELECT
               id
        ';

        $this->from = '
               FROM doorlock
        ';

        $this->where = '
        ';

        $this->order = '
            ORDER BY sc
        ';
    }

    function getAll() {
        return $this->query('', 'SKeyManager\Entity\Lock');
    }
}


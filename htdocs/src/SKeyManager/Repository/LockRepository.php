<?php

namespace SKeyManager\Repository;

class LockRepository extends AbstractRepository {

    protected $locationPattern = '/locks/show/%s';

    function __construct() {
        $this->select = '
            SELECT
               doorlock.id AS lockid,
               number,
               doorlock.name AS lockname,
               sc,
               comment,
               doorplace.name AS heim,
               doorlockstatus.name AS statusname
        ';

        $this->from = '
               FROM doorlock
               LEFT JOIN doorplace ON (doorlock.place = doorplace.id)
               LEFT JOIN doorlockstatus ON (doorlock.status = doorlockstatus.id)
        ';

        $this->order = '
            ORDER BY sc
        ';
    }

    function getBanByKeyId($id) {
        // return $this->query('WHERE doorperson.id = '.$id);
    }
}


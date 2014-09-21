<?php

namespace SKeyManager\Repository;

class KeyRepository extends AbstractRepository {

    protected $locationPattern = '/keys/show/%s';

    function __construct() {
        $this->select = '
            SELECT
                doorkey.id AS id,
                code,
                doorkeystatus.name AS statusname,
                doorperson.name AS owner,
                doorkey.comment AS keycomment
        ';

        $this->from = '
            FROM doorkey
            LEFT JOIN doorkeystatus ON (doorkey.status = doorkeystatus.id)
            LEFT JOIN doorperson ON (doorkey.owner = doorperson.id )
        ';

        $this->order = '
            ORDER BY code
        ';
    }

    function getByPersonId($id) {
        return $this->query('WHERE doorperson.id = '.$id, 'SKeyManager\Entity\Key');
    }

    function getByAllowedForLock($id) {
        return $this->query('WHERE doorperson.id = '.$id);
    }

    function getAll() {
        return $this->query('', 'SKeyManager\Entity\Key');
    }
}

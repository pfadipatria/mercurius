<?php

namespace SKeyManager\Repository;

class KeyRepository extends AbstractRepository {

    protected $locationPattern = '/keys/show/%s';

    function __construct() {
        $this->select = '
            SELECT
                doorkey.id,
                code,
                doorkeystatus.name AS statusname,
                doorkey.comment AS keycomment,
                doorperson.name AS owner
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
        return $this->query('WHERE doorperson.id = '.$id);
    }
}

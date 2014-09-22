<?php

namespace SKeyManager\Repository;

class PermissionRepository extends AbstractRepository {

    function __construct() {
        $this->select = '
            SELECT
               id
        ';

        $this->from = '
            FROM permission
        ';

        $this->where = '
        ';

        $this->order = '
        ';
    }

    function getByLockId($id) {
        return $this->query('WHERE lockid = '.$id, 'SKeyManager\Entity\Permission');
    }

    function getByKeyId($id) {
        return $this->query('WHERE keyid = '.$id, 'SKeyManager\Entity\Permission');
    }

    function getAllowedByKeyId($id) {
        return $this->query('WHERE `keyid` = '.$id.' AND `allows` = TRUE', 'SKeyManager\Entity\Permission');
    }

    function getDeniedByKeyId($id) {
        return $this->query('WHERE `keyid` = '.$id.' AND `denies` = TRUE', 'SKeyManager\Entity\Permission');
    }

    function getAll() {
        return $this->query('', 'SKeyManager\Entity\Permission');
    }
}

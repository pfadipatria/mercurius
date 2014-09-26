<?php

namespace SKeyManager\Repository;

class HistoryRepository extends AbstractRepository {

    function __construct() {
        $this->select = '
            SELECT
                id
        ';

        $this->where = '
        ';

        $this->from = '
            FROM `history`
        ';

        $this->order = '
            ORDER BY lastupdate DESC
            LIMIT 100
        ';
    }

    function getAll() {
        return $this->query('', 'SKeyManager\Entity\History');
    }

}

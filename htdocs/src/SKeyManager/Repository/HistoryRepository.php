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

    function getByPersonId($personId) {
        //  var_dump($personId);
        return $this->query(' WHERE personid = '.$personId.' ', 'SKeyManager\Entity\History');
        return $this->query('', 'SKeyManager\Entity\History');
    }

}

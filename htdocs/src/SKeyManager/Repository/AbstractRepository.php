<?php

namespace SKeyManager\Repository;

abstract class AbstractRepository {

    function getAll() {
        return $this->query();
    }

    protected function query($where = ' ') {
        var_dump($this);
        $con = openDb();
        $dbresult = queryDb($con, $this->select.$this->from.$where.$this->order);
        $rows = array();
        $locations = array();
        while ($row = mysqli_fetch_assoc($dbresult)){
            $locations[] = sprintf($this->locationPattern, $row['id']);
            $rows[] = $row;
        }

        return array($rows, $locations);
    }

}

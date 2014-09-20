<?php

namespace SKeyManager\Entity;

abstract class AbstractEntity {

    function getAll() {
        return $this->query();
    }

    protected function query() {
        $con = openDb();
        $dbresult = queryDb($con, $this->select.$this->from.$where.$this->order);
        $row = array();
        $locations = array();
        $row = mysqli_fetch_assoc($dbresult);
        return $row;
    }

}

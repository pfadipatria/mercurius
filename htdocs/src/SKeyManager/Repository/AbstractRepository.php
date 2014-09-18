<?php

namespace SKeyManager\Repository;

abstract class AbstractRepository {


    function getAll() {
        $con = openDb();
        $dbresult = queryDb($con, $this->query);
        $rows = array();
        $locations = array();
        while ($row = mysqli_fetch_assoc($dbresult)){
            $locations[] = sprintf($this->locationPattern, $row['id']);
            $rows[] = $row;
        }

        return array($rows, $locations);
    }

}

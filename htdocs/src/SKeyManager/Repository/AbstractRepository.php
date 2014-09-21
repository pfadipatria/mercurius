<?php

namespace SKeyManager\Repository;

abstract class AbstractRepository {

    function getAll() {
        return $this->query();
    }

    protected function query($where = ' ', $object = null) {
      $result = array();
      $con = openDb();
      $dbresult = queryDb($con, $this->select.$this->from.$this->where.$where.$this->order);
      while ($row = mysqli_fetch_assoc($dbresult)){
         $entity = new $object($row['id']);
         $entity->load();
         $result[] = $entity;
      }

      return $result;
    }
}

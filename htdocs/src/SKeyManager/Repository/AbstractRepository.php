<?php

namespace SKeyManager\Repository;

abstract class AbstractRepository {

    function getAll() {
        return $this->query();
    }

    protected function query($where = ' ', $object = null) {
      $con = openDb();
      var_dump($this->select.$this->from.$this->where.$where.$this->order);
      $dbresult = queryDb($con, $this->select.$this->from.$this->where.$where.$this->order);
      $rows = array();
      $locations = array();
      while ($row = mysqli_fetch_assoc($dbresult)){
         if ($object){
            $entity = new $object($row['id']);
            foreach ($row as $name => $value) {
               $entity->$name = $value;
            }
         } else {
            $entity = $row;
         }
         $locations[] = sprintf($this->locationPattern, $row['id']);
         $rows[] = $entity;
         
      }
      return array($rows, $locations);
    }

}
